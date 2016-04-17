<?php
namespace Arjf\Sainsburys\Service;

use Goutte\Client as Scraper;
use Arjf\Sainsburys\Service\Exception\EmptyResponse;

/**
 * Abstract class that defines the base functionality of the scrape related
 * services
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
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
    )
    {
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
        try {
            return $this->client->request('GET', $this->getUrl());
        } catch (Exception $ex) {
            if ($ex instanceof \InvalidArgumentException) {
                throw new EmptyResponse();
            }
        }
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
     */
    abstract public function getData();
}
