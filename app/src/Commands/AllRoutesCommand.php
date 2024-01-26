<?php

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand(
    name: 'app:all-routes',
    description: 'Showing all exist routes from controllers',
    hidden: false
)]
class AllRoutesCommand extends Command
{

    const MASK = "|%-20.20s |%-51.50s | %-30.30s  |\n";

    public function __construct(private RouterInterface $router)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();

        if (!count($allRoutes)) {
            echo "\033[31mRoutes not found!\e[39m", PHP_EOL;
            return Command::SUCCESS;
        }

        $border = fn() => '+' . str_repeat('-', 21) . '+' . str_repeat('-', 52) .
            '+' . str_repeat('-', 33) . '+'.PHP_EOL;

        // Draw table with routes
        echo $border(), sprintf(self::MASK, 'Name', 'Class::function', 'Uri'), $border();
        foreach ($allRoutes as $key => $value) {
            printf(self::MASK, $key, $value->getDefault('_controller'), $value->getPath());
        }
        echo $border();

        return Command::SUCCESS;
    }
}