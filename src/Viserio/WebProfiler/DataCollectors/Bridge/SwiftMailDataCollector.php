<?php
declare(strict_types=1);
namespace Viserio\WebProfiler\DataCollectors;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swift_Plugins_MessageLogger;
use Swift_Mailer;
use Viserio\Contracts\WebProfiler\MenuAware as MenuAwareContract;
use Viserio\Contracts\WebProfiler\PanelAware as PanelAwareContract;

class SwiftMailDataCollector extends AbstractDataCollector implements MenuAwareContract, PanelAwareContract
{
    /**
     * Swift_Plugins_MessageLogger instance.
     *
     * @var \Swift_Plugins_MessageLogger
     */
    protected $messagesLogger;

    /**
     * Create new swift mailer data collector instance.
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->messagesLogger = new Swift_Plugins_MessageLogger();

        $mailer->registerPlugin($this->messagesLogger);
    }

    /**
     * {@inheritdoc}
     */
    public function collect(ServerRequestInterface $serverRequest, ResponseInterface $response)
    {
        $mails = [];

        foreach ($this->messagesLogger->getMessages() as $message) {
            $mails[] = [
                'to' => $this->formatTo($message->getTo()),
                'subject' => $message->getSubject(),
                'headers' => $message->getHeaders()->toString()
            ];
        }

        $this->data = [
            'count' => count($mails),
            'mails' => $mails
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getMenu(): array
    {
        return [
            'icon' => file_get_contents(__DIR__ . '/../Resources/icons/ic_schedule_white_24px.svg'),
            'label' => 'Mails',
            'value' => $this->data['count'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getPanel(): string
    {
        return $this->createTable(
            $this->data['mails'],
            null,
            ['to', 'subject', 'headers']
        );
    }

    /**
     * Format to from message.
     *
     * @param array|null $to
     *
     * @return string
     */
    protected function formatTo(?array $to): string
    {
        if (! $to) {
            return '';
        }

        $f = [];

        foreach ($to as $k => $v) {
            $f[] = (empty($v) ? '' : "$v ") . "<$k>";
        }

        return implode(', ', $f);
    }
}
