<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Viserio\Component\Console\Event;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Viserio\Component\Console\ConsoleEvents;

class ConsoleCommandEvent extends ConsoleEvent
{
    /**
     * The return code for skipped commands, this will also be passed into the terminate event.
     */
    public const RETURN_CODE_DISABLED = 113;

    /**
     * Indicates if the command should be run or skipped.
     *
     * @var bool
     */
    private $commandShouldRun = true;

    /**
     * Create a new command event.
     *
     * @param \Symfony\Component\Console\Command\Command        $command
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct(Command $command, InputInterface $input, OutputInterface $output)
    {
        $this->name = ConsoleEvents::COMMAND;
        $this->target = $command;
        $this->parameters = ['input' => $input, 'output' => $output];
    }

    /**
     * Returns true if the command is runnable, false otherwise.
     *
     * @return bool
     */
    public function commandShouldRun(): bool
    {
        return $this->commandShouldRun;
    }

    /**
     * Disables the command, so it won't be run.
     *
     * @return bool
     */
    public function disableCommand(): bool
    {
        return $this->commandShouldRun = false;
    }

    /**
     * Enables the command.
     *
     * @return bool
     */
    public function enableCommand(): bool
    {
        return $this->commandShouldRun = true;
    }
}
