<?php

namespace Payments\Client\Entities;

use Carbon\Carbon;

class Beneficiary implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $system;

    /**
     * @var string
     */
    private $account;

    /**
     * @var Carbon
     */
    private $valid_until;

    /**
     * Beneficiary constructor.
     * @param string $name
     * @param string $account
     * @param Carbon $valid_until
     */
    public function __construct(string $name, string $account, Carbon $valid_until)
    {
        $this->name = $name;
        $this->account = $account;
        $this->valid_until = $valid_until;
        $this->system = config('payment.system');
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
        $system = $this->system;
        $account = $this->account;
        $valid_until = (string) $this->valid_until;

        return compact('name', 'system', 'account', 'valid_until');
    }
}