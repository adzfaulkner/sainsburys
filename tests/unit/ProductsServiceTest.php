<?php
use Arjf\Sainsburys\Service\ProductsService;
use Goutte\Client;
use GuzzleHttp\Message\Response as GuzzleResponse;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;

/**
 * Need to test the request by mocking trying not to leave any test smell
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class ProductsServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * subroutine executed before every test
     */
    public function setUp()
    {
        $this->client = new Client();
    }

    /**
     * Test to make sure that an empty response will throw the expected exception
     *
     * @expectedException \Arjf\Sainsburys\Service\Exception\UnexpectedResponseException
     */
    public function testWithEmptyResponse()
    {
        $mock = new Mock();
        $mock->addResponse(new GuzzleResponse(404, array(), Stream::factory('')));

        $guzzle = $this->getGuzzle();
        $guzzle->getEmitter()->attach($mock);

        $client = $this->client;
        $client->setClient($guzzle);

        $productService = Phake::mock('\Arjf\Sainsburys\Service\ProductService');

        $service = new ProductsService($client, $productService);
        $service->getData();
    }

    /**
     * Test to make sure that an unexpected response will throw the expected exception
     *
     * @expectedException \Arjf\Sainsburys\Service\Exception\UnexpectedResponseException
     */
    public function testWithUnexpectedResponse()
    {
        $mock = new Mock();
        $mock->addResponse(new GuzzleResponse(200, array(), Stream::factory($this->getMockedResponse('unexpected'))));

        $guzzle = $this->getGuzzle();
        $guzzle->getEmitter()->attach($mock);

        $client = $this->client;
        $client->setClient($guzzle);

        $productService = Phake::mock('\Arjf\Sainsburys\Service\ProductService');

        $service = new ProductsService($client, $productService);
        $service->getData();
    }

    /**
     * Test to make sure that given the expected repsonse, a collection is returned
     */
    public function testWithExpectedResponse()
    {
        $mock = new Mock();
        $mock->addResponse(new GuzzleResponse(200, array(), Stream::factory($this->getMockedResponse('expected'))));

        $guzzle = $this->getGuzzle();
        $guzzle->getEmitter()->attach($mock);

        $client = $this->client;
        $client->setClient($guzzle);

        $productModel = Phake::mock('\Arjf\Sainsburys\Model\ProductModel');

        $productService = Phake::mock('\Arjf\Sainsburys\Service\ProductService');
        Phake::when($productService)->getData()->thenReturn($productModel);

        $service = new ProductsService($client, $productService);
        $collection= $service->getData();

        $this->assertInstanceOf('\Arjf\Sainsburys\Collection\ProductCollection', $collection);
        $this->assertFalse($collection->isEmpty());
        $this->assertEquals(7, count($collection->jsonSerialize()['results']));
    }

    /**
     * Test to make sure as long as the expected html exists in the response the
     * service will still return a collection
     */
    public function testWithOtherResponse()
    {
        $mock = new Mock();
        $mock->addResponse(
            new GuzzleResponse(
                200,
                array(),
                Stream::factory($this->getMockedResponse('other'))
            )
        );

        $guzzle = $this->getGuzzle();
        $guzzle->getEmitter()->attach($mock);

        $client = $this->client;
        $client->setClient($guzzle);

        $productModel = Phake::mock('\Arjf\Sainsburys\Model\ProductModel');

        $productService = Phake::mock('\Arjf\Sainsburys\Service\ProductService');
        Phake::when($productService)->getData()->thenReturn($productModel);

        $service = new ProductsService($client, $productService);
        $collection= $service->getData();

        $this->assertInstanceOf('\Arjf\Sainsburys\Collection\ProductCollection', $collection);
        $this->assertFalse($collection->isEmpty());
        $this->assertEquals(2, count($collection->jsonSerialize()['results']));
    }

    /**
     * Creates a new guzzle object
     *
     * @return GuzzleClient
     */
    protected function getGuzzle()
    {
        return Utils::getGuzzle();
    }

    /**
     * returns the resource out from a mock file
     *
     * @param string $name
     * @return resource
     */
    protected function getMockedResponse($name)
    {
        return Utils::getProductsMock($name);
    }
}
