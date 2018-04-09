<?php
declare(strict_types=1);
namespace Viserio\Component\Session\Tests\Handler;

use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Viserio\Component\Session\Handler\PdoSessionHandler;
use Viserio\Component\Session\Tests\Fixtures\MockPdo;

/**
 * @requires extension pdo_sqlite
 * @group time-sensitive
 */
class PdoSessionHandlerTest extends TestCase
{
    private const TTL = 300;

    /**
     * @var string
     */
    private $dbFile;

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        // make sure the temporary database file is deleted when it has been created (even when a test fails)
        if ($this->dbFile) {
            @\unlink($this->dbFile);
        }

        parent::tearDown();
    }

    public function testInstanceOf(): void
    {
        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL);

        self::assertInstanceOf(\SessionHandlerInterface::class, $handler);
        self::assertInstanceOf(\SessionUpdateTimestampHandlerInterface::class, $handler);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongPdoErrMode(): void
    {
        $pdo = $this->getMemorySqlitePdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

        new PdoSessionHandler($pdo, self::TTL);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInexistentTable(): void
    {
        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL, ['db_table' => 'inexistent_table']);
        $handler->open('', 'sid');
        $handler->read('id');
        $handler->write('id', 'data');
        $handler->close();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCreateTableTwice(): void
    {
        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL);
        $handler->createTable();
    }

    public function testWithLazyDsnConnection(): void
    {
        $dsn = $this->getPersistentSqliteDsn();

        $handler = new PdoSessionHandler($dsn, self::TTL);
        $handler->createTable();
        $handler->open('', 'sid');
        $data = $handler->read('id');
        $handler->write('id', 'data');
        $handler->close();

        self::assertSame('', $data, 'New session returns empty string data');

        $handler->open('', 'sid');
        $data = $handler->read('id');
        $handler->close();

        self::assertSame('data', $data, 'Written value can be read back correctly');
    }

    public function testWithLazySavePathConnection(): void
    {
        $dsn = $this->getPersistentSqliteDsn();

        // Open is called with what ini_set('session.save_path', $dsn) would mean
        $handler = new PdoSessionHandler(null, self::TTL);
        $handler->open($dsn, 'sid');
        $handler->createTable();
        $data = $handler->read('id');
        $handler->write('id', 'data');
        $handler->close();

        self::assertSame('', $data, 'New session returns empty string data');

        $handler->open($dsn, 'sid');
        $data = $handler->read('id');
        $handler->close();

        self::assertSame('data', $data, 'Written value can be read back correctly');
    }

    public function testReadWriteReadWithNullByte(): void
    {
        $sessionData = 'da' . "\0" . 'ta';

        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL);
        $handler->open('', 'sid');
        $readData = $handler->read('id');
        $handler->write('id', $sessionData);
        $handler->close();

        self::assertSame('', $readData, 'New session returns empty string data');

        $handler->open('', 'sid');
        $readData = $handler->read('id');
        $handler->close();

        self::assertSame($sessionData, $readData, 'Written value can be read back correctly');
    }

    public function testReadConvertsStreamToString(): void
    {
        $pdo                = new MockPdo('pgsql');
        $pdo->prepareResult = $this->getMockBuilder('PDOStatement')->getMock();

        $content = 'foobar';
        $stream  = $this->createStream($content);

        $pdo->prepareResult->expects($this->once())->method('fetchAll')
            ->will($this->returnValue([[$stream, 42, \time()]]));

        $handler = new PdoSessionHandler($pdo, self::TTL);
        $result  = $handler->read('foo');

        self::assertSame($content, $result);
    }

    public function testReadLockedConvertsStreamToString(): void
    {
        if (ini_get('session.use_strict_mode')) {
            $this->markTestSkipped('Strict mode needs no locking for new sessions.');
        }

        $pdo        = new MockPdo('pgsql');
        $selectStmt = $this->getMockBuilder('PDOStatement')->getMock();
        $insertStmt = $this->getMockBuilder('PDOStatement')->getMock();

        $pdo->prepareResult = function ($statement) use ($selectStmt, $insertStmt) {
            return \mb_strpos($statement, 'INSERT') === 0 ? $insertStmt : $selectStmt;
        };

        $content   = 'foobar';
        $stream    = $this->createStream($content);
        $exception = null;

        $selectStmt->expects($this->atLeast(2))->method('fetchAll')
            ->will($this->returnCallback(function () use (&$exception, $stream) {
                return $exception ? [[$stream, 42, \time()]] : [];
            }));

        $insertStmt->expects($this->once())->method('execute')
            ->will($this->returnCallback(function () use (&$exception): void {
                throw $exception = new PDOException('', 23);
            }));

        $handler = new PdoSessionHandler($pdo, self::TTL);
        $result  = $handler->read('foo');

        self::assertSame($content, $result);
    }

    public function testReadingRequiresExactlySameId(): void
    {
        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL);
        $handler->open('', 'sid');
        $handler->write('id', 'data');
        $handler->write('test', 'data');
        $handler->write('space ', 'data');
        $handler->close();

        $handler->open('', 'sid');

        $readDataCaseSensitive = $handler->read('ID');
        $readDataNoCharFolding = $handler->read('tést');
        $readDataKeepSpace     = $handler->read('space ');
        $readDataExtraSpace    = $handler->read('space  ');

        $handler->close();

        self::assertSame('', $readDataCaseSensitive, 'Retrieval by ID should be case-sensitive (collation setting)');
        self::assertSame('', $readDataNoCharFolding, 'Retrieval by ID should not do character folding (collation setting)');
        self::assertSame('data', $readDataKeepSpace, 'Retrieval by ID requires spaces as-is');
        self::assertSame('', $readDataExtraSpace, 'Retrieval by ID requires spaces as-is');
    }

    /**
     * Simulates session_regenerate_id(true) which will require an INSERT or UPDATE (replace).
     */
    public function testWriteDifferentSessionIdThanRead(): void
    {
        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL);
        $handler->open('', 'sid');
        $handler->read('id');
        $handler->destroy('id');
        $handler->write('new_id', 'data_of_new_session_id');
        $handler->close();

        $handler->open('', 'sid');

        $data = $handler->read('new_id');

        $handler->close();

        self::assertSame('data_of_new_session_id', $data, 'Data of regenerated session id is available');
    }

    public function testWrongUsageStillWorks(): void
    {
        // wrong method sequence that should no happen, but still works
        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL);
        $handler->write('id', 'data');
        $handler->write('other_id', 'other_data');
        $handler->destroy('inexistent');
        $handler->open('', 'sid');

        $data      = $handler->read('id');
        $otherData = $handler->read('other_id');

        $handler->close();

        self::assertSame('data', $data);
        self::assertSame('other_data', $otherData);
    }

    public function testSessionDestroy(): void
    {
        $pdo     = $this->getMemorySqlitePdo();
        $handler = new PdoSessionHandler($pdo, self::TTL);

        $handler->open('', 'sid');
        $handler->read('id');
        $handler->write('id', 'data');
        $handler->close();

        self::assertEquals(1, $pdo->query('SELECT COUNT(*) FROM sessions')->fetchColumn());

        $handler->open('', 'sid');
        $handler->read('id');
        $handler->destroy('id');
        $handler->close();

        self::assertEquals(0, $pdo->query('SELECT COUNT(*) FROM sessions')->fetchColumn());

        $handler->open('', 'sid');
        $data = $handler->read('id');
        $handler->close();

        self::assertSame('', $data, 'Destroyed session returns empty string');
    }

    public function testSessionGC(): void
    {
        $pdo     = $this->getMemorySqlitePdo();
        $handler = new PdoSessionHandler($pdo, 1000);

        $handler->open('', 'sid');
        $handler->read('id');
        $handler->write('id', 'data');
        $handler->close();

        $handler->open('', 'sid');
        $handler->read('gc_id');

        self::assertEquals(1, $pdo->query('SELECT COUNT(*) FROM sessions')->fetchColumn(), 'No session pruned because gc not called');

        $handler->open('', 'sid');
        $data = $handler->read('gc_id');
        $handler->gc(-1);
        $handler->close();

        self::assertSame('', $data, 'Session already considered garbage, so not returning data even if it is not pruned yet');
        self::assertEquals(1, $pdo->query('SELECT COUNT(*) FROM sessions')->fetchColumn(), 'Expired session is pruned');
    }

    public function testGetConnection(): void
    {
        $handler = new PdoSessionHandler($this->getMemorySqlitePdo(), self::TTL);

        $method = new ReflectionMethod($handler, 'getConnection');
        $method->setAccessible(true);

        self::assertInstanceOf(PDO::class, $method->invoke($handler));
    }

    public function testGetConnectionConnectsIfNeeded(): void
    {
        $handler = new PdoSessionHandler('sqlite::memory:', self::TTL);

        $method = new ReflectionMethod($handler, 'getConnection');
        $method->setAccessible(true);

        self::assertInstanceOf(PDO::class, $method->invoke($handler));
    }

    private function getPersistentSqliteDsn()
    {
        $this->dbFile = \tempnam(\sys_get_temp_dir(), 'sf2_sqlite_sessions');

        return 'sqlite:' . $this->dbFile;
    }

    private function getMemorySqlitePdo()
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $handler = new PdoSessionHandler($pdo, self::TTL);
        $handler->createTable();

        return $pdo;
    }

    private function createStream($content)
    {
        $stream = \tmpfile();

        \fwrite($stream, $content);
        \fseek($stream, 0);

        return $stream;
    }
}
