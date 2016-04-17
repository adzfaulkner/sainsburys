<?php

namespace Arjf\Sainsburys\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Arjf\Sainsburys\Service\ProductsService;

/**
 * The entry point to execute the command
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class ProductCommand extends Command
{
    /**
     *
     * @var ProductsService
     */
    protected $productsService;

    /**
     * Override the contractor so that we can inject dependencies
     *
     * @param ProductService $productService
     * @param type $name
     */
    public function __construct(
        ProductsService $productsService,
        $name = null
    )
    {
        parent::__construct($name);
        $this->productsService = $productsService;
    }

    /**
     * Basically the setup of the command line app
     */
    protected function configure()
    {
        $start = 0;
        $stop  = 100;

        $this->setName("sainsburys:products")
            ->setDescription("Render a list of products in json")
            ->setHelp(<<<EOT
No arguments are necessary whilst executing this app, simply type in the command
EOT
        );
    }

    /**
     * Executes the command line app
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $productService = $this->productsService;
        $output->writeln(json_encode($productService->getData()));
    }
}