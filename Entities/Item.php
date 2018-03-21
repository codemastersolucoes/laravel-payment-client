<?php

namespace Payments\Client\Entities;


class Item implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var double
     */
    private $amount;

    /**
     * @var double
     */
    private $discount_amount;

    /**
     * @var double
     */
    private $final_value;

    /**
     * @var array
     */
    private $discounts;

    /**
     * @var string
     */
    private $beneficiary;

    /**
     * @var array
     */
    private $details;

    /**
     * Item constructor.
     * @param string $name
     * @param float $amount
     * @param float $discount_amount
     * @param float $final_value
     * @param string $beneficiary
     */
    public function __construct(string $name,
                                double $amount,
                                double $discount_amount,
                                double $final_value,
                                string $beneficiary)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->discount_amount = $discount_amount;
        $this->final_value = $final_value;
        $this->discounts = [];
        $this->beneficiary = $beneficiary;
        $this->details = [];
    }

    /**
     * @param Detail $detail
     */
    public function addDetail(Detail $detail)
    {
        $this->details[] = $detail;
    }

    public function addDiscount(string $key, double $value)
    {
        $this->discounts[$key] = $value;
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
        $amount = $this->amount;
        $discount_amount = $this->discount_amount;
        $final_value = $this->final_value;
        $discounts = $this->discounts;
        $beneficiary = $this->beneficiary;

        $details = [];
        /** @var Detail $detail */
        foreach ($this->details  as $detail) {
            $details[] = $detail->jsonSerialize();
        }

        $result = compact('name', 'amount', 'discount_amount', 'final_value', 'beneficiary', 'details');

        if (!empty($discounts)) {
            $result = array_merge($result, compact('discounts'));
        }

        return $result;
    }
}