<?php

namespace Arjf\Sainsburys\Model;

/**
 * Basic model to hold the data and define how the json format
 *
 * @author Adam Faulkner<adzfaulkner@hotmail.com>
 */
class ProductModel implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var float
     */
    protected $unitPrice;

    /**
     * @var description
     */
    protected $description;

    /**
     *
     * @param string $title
     * @param int $size
     * @param type $unitPrice
     * @param type $description
     */
    public function __construct(
        $title,
        $size,
        $unitPrice,
        $description
    )
    {
        $this->title = $title;
        $this->size = (int) $size;
        $this->unitPrice = (float) $unitPrice;
        $this->description = $description;
    }

    /**
     * Returns the title of the product
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the size of the full description URL in bytes allowing the user
     * to define their own algorithm
     * 
     * @todo create oop strategies to convert to Kilobyes, Megabytes
     * @param callable $algorithm
     * @return mixed
     */
    public function getSize(callable $algorithm = null)
    {
        if (is_callable($algorithm)) {
            return $algorithm($this->size);
        }

        return $this->size;
    }

    /**
     * Returns the file size in KB
     *
     * @return float|int
     */
    public function getSizeInKb()
    {
        return $this->getSize(function($size) {
            return round($size / 1024);
         });
    }

    /**
     * Returns the unit price of the product
     * 
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Returns the description of the product
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns an array of data that can be json serialized
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return [
            'title' => $this->getTitle(),
            'size' => $this->getSizeInKb() . 'kb',
            'unit_price' => $this->getUnitPrice(),
            'description' => $this->getDescription(),
        ];
    }
}