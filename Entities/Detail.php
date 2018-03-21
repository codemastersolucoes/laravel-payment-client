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
     * Detail constructor.
     * @param string $item
     * @param float $value
     */
    public function __construct(string $item, float $value)
    {
        $this->item = $item;
        $this->value = $value;
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

        return compact('item', 'value');
    }
}