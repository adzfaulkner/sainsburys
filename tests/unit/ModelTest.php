<?php
use Arjf\Sainsburys\Model\ProductModel;

/**
 * The model class has become testable as unit size getter accepts a strategy
 * type parameter allowing the user to define their own algorithm. Plus need to
 * test the json serialize method
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test to make sure that the get size method can accept a callable and the
     * return value is what is expected
     */
    public function testSizeWithCallable()
    {
        $product = new ProductModel('title', 1024, 1.00, 'description');
        $this->assertEquals(
            1,
            $product->getSize(function($size) {
                return $size / 1024;
            })
        );
    }

    /**
     * Test to make sure that the size returned is what was put in as no
     * callable was not passed in
     */
    public function testSizeWithoutCallable()
    {
        $product = new ProductModel('title', 2024, 1.00, 'description');
        $this->assertEquals(2024, $product->getSize());
    }

    /**
     * Test to make sure that size is in kb
     */
    public function testSizeInKb()
    {
        $product = new ProductModel('title', 2048, 1.00, 'description');
        $this->assertEquals(2, $product->getSizeInKb());
    }

    /**
     * Test to make sure that the json serialize function returns the expected
     * array and serialized result matches serialized expected
     */
    public function testSerialized()
    {
        $product = new ProductModel('title', 2048, 1.00, 'description');

        $expected = [
            'title' => 'title',
            'size' => '2kb',
            'unit_price' => 1.00,
            'description' => 'description'
        ];

        $this->assertEquals($expected, $product->jsonSerialize());
        $this->assertEquals(json_encode($expected), json_encode($product));
    }
}
