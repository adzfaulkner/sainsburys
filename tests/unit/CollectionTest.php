<?php
use Arjf\Sainsburys\Collection\ProductCollection;

/**
 * The collection has become testable as it returns as it has a dependency on
 * the product model class
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test to make sure that the add method is fluent and adds the model to the
     * internal collection object
     */
    public function testAddProduct()
    {
        $productMock = Phake::mock('\Arjf\Sainsburys\Model\ProductModel');
        $collection = Phake::partialMock('\Arjf\Sainsburys\Collection\ProductCollection');

        $this->assertInstanceOf('\Arjf\Sainsburys\Collection\ProductCollection', $collection->add($productMock));
        $this->assertSame($productMock, Phake::makeVisible($collection)->getCollection()[0]);
    }

    /**
     * Test to make sure the json returned is what is expected
     */
    public function testJsonSerialize()
    {
        $data = [
            'results' => array(
                array(
                    'title' => 'product1',
                    'unit_price' => 2.00,
                    'size' => 1024,
                    'description' => 'testing',
                ),
                array(
                    'title' => 'product2',
                    'unit_price' => 4.00,
                    'size' => 1024,
                    'description' => 'testing',
                ),
                array(
                    'title' => 'product3',
                    'unit_price' => 4.50,
                    'size' => 1024,
                    'description' => 'testing',
                ),
            ),
            'total' => 10.50
        ];

        $collection = new ProductCollection();

        foreach ($data['results'] as $product) {
            $productMock = Phake::mock('\Arjf\Sainsburys\Model\ProductModel');

            Phake::when($productMock)->jsonSerialize()->thenReturn(
                $product
            );

            Phake::when($productMock)->getUnitPrice()->thenReturn(
                $product['unit_price']
            );

            $collection->add($productMock);
        }

        $this->assertEquals(json_encode($data), json_encode($collection));
    }

}
