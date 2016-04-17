<?php

namespace Arjf\Sainsburys\Service;

use Arjf\Sainsburys\Collection\ProductCollection;
use Arjf\Sainsburys\Service\ProductService;
use Goutte\Client;
use Arjf\Sainsburys\Service\Exception\UnexpectedResponseException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Service class to do most of the donkey work required such as:
 *
 * 1: Make the request
 * 2: Crawl through the dom
 * 3: Generate a product model and add it to the collection
 * 4: Return the collection
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class ProductsService extends AbstractScrape
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * Overwritting constructor to allow the injection of the product service
     * 
     * @param Client $client
     * @param ProductService $productService
     */
    public function __construct(
        Client $client,
        ProductService $productService
    )
    {
        parent::__construct($client);
        $this->productService = $productService;
    }

    /**
     * Generates the Collection object from the data received from the requests
     *
     * @return ProductCollection
     * @throws \LengthException
     */
    public function getData()
    {
        $productService = $this->productService;
        $crawler = $this->makeRequest();
        $filter = $crawler->filter('.productInner');

        if ($filter->count() < 1) {
            throw new UnexpectedResponseException(__METHOD__ . ' can not find dom selector .productInner');
        }

        $collection = new ProductCollection();

        // Get the latest post in this category and display the titles
        $filter->each(function (Crawler $crawler) use ($collection, $productService) {
            $productService->setUrl($this->getPageUrl($crawler));
            $collection->add($productService->getData());
        });

        return $collection;
    }

    /**
     * Retruns the page url for a given crawler block
     *
     * @param Crawler $crawler
     * @return type
     */
    protected function getPageUrl(Crawler $crawler)
    {
        return $crawler->filter('h3 > a')->first()->attr('href');
    }
}