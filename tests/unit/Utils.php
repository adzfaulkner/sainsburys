<?php
use GuzzleHttp\Client as GuzzleClient;

/**
 * Various utils to aid tests
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class Utils
{

    /**
     * returns a new guzzle object used for mocking
     *
     * @param string $name
     * @return resource
     */
    public static function getGuzzle()
    {
        $guzzle = new GuzzleClient(array('redirect.disable' => true, 'base_url' => ''));
        return $guzzle;
    }

    /**
     * returns a resource for a given product mock
     *
     * @param string $name
     * @return resource
     */
    public static function getProductMock($name)
    {
        return self::getMock($name, 'product');
    }

    /**
     * returns a resource for a given products mock
     *
     * @param string $name
     * @return resource
     */
    public static function getProductsMock($name)
    {
        return self::getMock($name, 'products');
    }

    /**
     * returns a resource for a given mock
     *
     * @param string $name
     * @param string $type
     * @return resource
     */
    protected static function getMock($name, $type)
    {
        return fopen(
            dirname(__FILE__) . '/mock/' . $type . '/body/' . $name . '.html',
            'r'
        );
    }

}

