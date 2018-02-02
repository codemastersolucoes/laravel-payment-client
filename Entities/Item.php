<?php

namespace Payments\Client\Entities;


class Item implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $details;

    /**
     * @var string
     */
    private $beneficiary;

    /**
     * Item constructor.
     * @param string $name
     * @param string $beneficiary
     */
    public function __construct(string $name, string $beneficiary)
    {
        $this->name = $name;
        $this->beneficiary = $beneficiary;
        $this->details = [];
    }

    public function addDetail(string $item, float $value)
    {
        $this->details[] = compact('item', 'value');
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
        $name = $this->name;
        $beneficiary = $this->beneficiary;
        $details = $this->details;

        return compact('name', 'beneficiary', 'details');
    }
}