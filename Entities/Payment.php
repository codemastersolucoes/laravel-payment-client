<?php


namespace Payments\Client\Entities;


class Payment implements \JsonSerializable
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var float
     */
    private $discount;

    /**
     * @var array
     */
    private $items;

    /**
     * Payment constructor.
     * @param float $value
     * @param float $discount
     */
    public function __construct(float $value, float $discount)
    {
        $this->value = $value;
        $this->discount = $discount;
        $this->items = [];
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() : array
    {
        $value = $this->value;
        $discount = $this->discount;
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item.jsonSerialize();
        }
        return compact('value', 'discount', 'items');
    }
}