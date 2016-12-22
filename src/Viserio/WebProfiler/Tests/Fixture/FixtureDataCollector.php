<?php
declare(strict_types=1);
namespace Viserio\WebProfiler\Tests\Fixture;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Viserio\WebProfiler\DataCollectors\AbstractDataCollector;

class FixtureDataCollector extends AbstractDataCollector
{
    /**
     * {@inheritdoc}
     */
    public function collect(ServerRequestInterface $serverRequest, ResponseInterface $response)
    {
    }

    public function getTableDefault()
    {
        return $this->createTable(['test key' => 'test value'], 'test');
    }

    public function getTableArray()
    {
        return $this->createTable([['test key' => 'test value']], 'array');
    }

    public function getTooltippGroupDefault()
    {
        return $this->createTooltipGroup([
            'test' => 'test',
        ]);
    }

    public function getTooltippGroupArray()
    {
        return $this->createTooltipGroup([
            'test' => [
                [
                    'class' => 'test',
                    'value' => 'test',
                ],
                [
                    'class' => 'test2',
                    'value' => 'test2',
                ],
            ],
        ]);
    }

    public function getTabs()
    {
        return $this->createTabs([
            [
                'name'    => 'test',
                'content' => 'test',
            ],
        ]);
    }

    public function getDropdownMenuContent()
    {
        return $this->createDropdownMenuContent([
            'dropdown' => 'content',
        ]);
    }
}
