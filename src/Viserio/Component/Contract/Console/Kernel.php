<?php
declare(strict_types=1);
namespace Viserio\Component\Contract\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Viserio\Component\Contract\Foundation\Kernel as BaseKernel;

interface Kernel extends BaseKernel
{
    /**
     * Handle an incoming console command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface        $input
     * @param null|\Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function handle(InputInterface $input, OutputInterface $output = null): int;

    /**
     * Get all of the commands registered with the console.
     *
     * @return array
     */
    public function getAll(): array;
}