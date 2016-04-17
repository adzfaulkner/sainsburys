<?php
namespace Arjf\Sainsburys\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Arjf\Sainsburys\Model\ProductModel;

/**
 * Basic bridge that composits doctrine's array collection as I wanted to
 * inherit that functionality however restrict what can be called on it
 *
 * @author Adam Faulkner <adzfaulkner@hotmail.com>
 */
class ProductCollection implements \JsonSerializable
{
    /**
     * @var ArrayCollection
     */
    protected $collection;

    /**
     * Class constructor which creates the instance to the internal
     * ArrayCollection object
     */
    public function __construct()
    {
        $this->collection = new ArrayCollection();
    }

    /**
     * Primarily defined so that the collection property can be obtained by
     * a unit test.
     *
     * @return ArrayCollection
     */
    protected function getCollection()
    {
        return $this->collection;
    }

    /**
     * Returns whether or not any models have been registered
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->getCollection()->isEmpty();
    }

    /**
     * Simply a wrapper for the ArrayCollection::add method with model
     * typehinting.
     *
     * @param ProductModel $model
     * @return $this
     */
    public function add(ProductModel $model)
    {
        $this->getCollection()->add($model);
        return $this;
    }

    /**
     * Returns the sum price total of all product models registered
     *
     * @return float
     */
    public function getSumTotal()
    {
        $sum = 0;

        foreach ($this->getCollection() as $model) {
            $sum += $model->getUnitPrice();
        }

        return $sum;
    }

    /**
     * Returns an array of data to be serialized into json.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'results' => $this->getCollection()->toArray(),
            'total' => $this->getSumTotal(),
        ];
    }
}
