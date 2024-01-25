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

    public function __construct(private RouterInterface $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        print '123';

        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();
        foreach ($allRoutes as $key => $value) {

            $data = $value->getDefaults();
            print_r($data);

//            if (u($name)->startsWith('blog_')) {
                echo $key.' - '.$value->getDefault('_controller').' - '.$value->getPath().PHP_EOL;
//            }
        }


        return Command::SUCCESS;
    }
}