<?php

namespace Arjf\Sainsburys\Service;
use Arjf\Sainsburys\Model\ProductModel;
use Arjf\Sainsburys\Service\Exception\UnexpectedResponseException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Service class mainly responsible to hydrate a ProductModel from scraped data
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class ProductService extends AbstractScrape
{

    /**
     * Makes a request to the product page and scrapes the data to generate a
     * product model
     *
     * @return ProductModel
     */
    public function getData()
    {
        $crawler = $this->makeRequest();

        try {
            $size = $this->client
                ->getResponse()
                ->getHeader('Content-Length');

            return new ProductModel(
                $this->getTitle($crawler),
                $size,
                $this->getPrice($crawler),
                $this->getDescription($crawler)
            );
        } catch (\InvalidArgumentException $e) {
            if (
                $e->getMessage() === 'The current node list is empty.'
            ) {
                throw new UnexpectedResponseException(__METHOD__ . ' unable to find necessary dom elements');
            }

            throw $e;
        }
    }

    /**
     * Helper method to abstract the title from the crawler
     *
     * @param Crawler $crawler
     * @return type
     */
    protected function getTitle(Crawler $crawler)
    {
        return $crawler->filter('.productTitleDescriptionContainer > h1')
                ->text();
    }

    /**
     * Helper method to abstract the price from the crawler
     *
     * @param Crawler $crawler
     * @return type
     */
    protected function getPrice(Crawler $crawler)
    {
        return preg_replace('/[^\d\.]/', '', $crawler->filter('.pricePerUnit')
                ->text());
    }

    /**
     * Helper method to abstract the description from the crawler
     *
     * @param Crawler $crawler
     * @return type
     */
    protected function getDescription(Crawler $crawler)
    {
        return $crawler->filter('.productText > p')
            ->first()
            ->text();
    }

}