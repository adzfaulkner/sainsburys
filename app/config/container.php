<?php
use Pimple\Container;
use Goutte\Client;
use Arjf\Sainsburys\Command\ProductCommand;
use Arjf\Sainsburys\Service\ProductsService;
use Arjf\Sainsburys\Service\ProductService;

/**
 * Define pimple containers for dependency injection
 *
 * @author Adam Faulkner <adzfaulkner@hotmail.com>
 */
$container = new Container();

$container['goute_client'] = $container->factory(function ($c) {
    return new Client();
});

$container['service_product'] = function($c) {
    return new ProductService($c['goute_client']);
};

$container['service_products'] = function ($c) {
    $productsService =  new ProductsService(
        $c['goute_client'],
        $c['service_product']
    );

    $productsService->setUrl('http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html');

    return $productsService;
};

$container['command_product'] = function ($c) {
    return new ProductCommand($c['service_products']);
};
