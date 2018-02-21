<?php

namespace Payments\Client\Entities;


class Detail implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $item;

    /**
     * @var float
     */
    protected $value;

    /**
     * @var array
     */
    protected $discounts;

    /**
     * Detail constructor.
     * @param string $item
     * @param float $value
     */
    public function __construct(string $item, float $value)
    {
        $this->item = $item;
        $this->value = $value;
        $this->discounts = [];
    }

    /**
     * @param float $discount
     * @param string $reason
     */
    public function addDiscount(float $discount, string $reason)
    {
        $this->discounts[] = compact('discount', 'reason');
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
        $item = $this->item;
        $value = $this->value;
        $discounts = $this->discounts;

        return compact('item', 'value', 'discounts');
    }
}