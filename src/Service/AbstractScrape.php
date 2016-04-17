<?php
namespace Arjf\Sainsburys\Service;

use Goutte\Client as Scraper;

/**
 * Abstract class that defines the base functionality of the scrape related
 * services
 *
 * @author Adam Faulkner <adzfaulkner@hotmail.com>
 */
abstract class AbstractScrape
{
    /**
     * @var Scrapper
     */
    protected $client;

    /**
     * @var string
     */
    protected $url;

    /**
     * Constructor assigns the properties
     *
     * @param Scraper $client
     */
    public function __construct(
        Scraper $client
    ) {
        $this->client = $client;
    }

    /**
     * Make the request and returns the crawler object
     *
     * @todo Add a check to make sure the URL is valid
     * @param Scraper $client
     * @param string $url
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function makeRequest()
    {
        return $this->client->request('GET', $this->getUrl());
    }

    /**
     * Returns the defined URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the URL for the internal client to use to scrape
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Where the main work of the object will occur
     *
     * @return mixed
     */
    abstract public function getData();
}
