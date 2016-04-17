<?php
use Arjf\Sainsburys\Service\ProductService;
use Arjf\Sainsburys\Model\ProductModel;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Response as GuzzleResponse;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;

/**
 * Need to test the request by mocking trying not to leave any test smell
 *
 * @author Adam Faulkner <adzfaulkner@hotmail.com>
 */
class ProductServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Client
     */
    protected $client;

    /**
     * @var ProductModel
     */
    protected static $expected;

    /**
     * subroutine executed before any tests are run
     */
    public static function setUpBeforeClass()
    {
        self::$expected = new ProductModel(
            'Sainsbury\'s Apricot Ripe & Ready x5',
            0,
            3.50,
            'Apricots'
        );
    }

    /**
     * subroutine executed before every test
     */
    public function setUp()
    {
        $this->client = new Client();
    }

    /**
     * Test to make sure that if an empty response is returned then an
     * apprioriate exception is thrown
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

        $service = new ProductService($client);
        $service->getData();
    }

    /**
     * Test to make sure that if the outer html changes then it does not impact
     * the DOM extraction
     */
    public function testWithOtherResponse()
    {
        $mock = new Mock();
        $mock->addResponse(new GuzzleResponse(200, array(),
            Stream::factory($this->getMockedResponse('other'))));

        $guzzle = $this->getGuzzle();
        $guzzle->getEmitter()->attach($mock);

        $client = $this->client;
        $client->setClient($guzzle);

        $service = new ProductService($client);
        $this->assertEquals(self::$expected, $service->getData());
    }

    /**
     * Test to make sure that if an empty response is returned then an
     * apprioriate exception is thrown
     *
     * @expectedException \Arjf\Sainsburys\Service\Exception\UnexpectedResponseException
     */
    public function testWithUnexpectedResponse()
    {
        $mock = new Mock();
        $mock->addResponse(new GuzzleResponse(200, array(),
            Stream::factory($this->getMockedResponse('unexpected'))));

        $guzzle = $this->getGuzzle();
        $guzzle->getEmitter()->attach($mock);

        $client = $this->client;
        $client->setClient($guzzle);

        $service = new ProductService($client);
        $service->getData();
    }

    /**
     * Test to make sure that given the expected response, the expected model
     * is returned
     */
    public function testWithExpectedResponse()
    {
        $mock = new Mock();
        $mock->addResponse(new GuzzleResponse(200, array(),
            Stream::factory($this->getMockedResponse('expected'))));

        $guzzle = $this->getGuzzle();
        $guzzle->getEmitter()->attach($mock);

        $client = $this->client;
        $client->setClient($guzzle);

        $service = new ProductService($client);
        $this->assertEquals(self::$expected, $service->getData());
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
        return Utils::getProductMock($name);
    }
}
