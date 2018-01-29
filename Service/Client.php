<?php


namespace Payments\Client\Service;


use Carbon\Carbon;


class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => config('payment.server'),
            'headers' => ['Accept' => 'application/json']
        ]);
    }

    /**
     * @param string $name
     * @param string $account
     * @param Carbon $valid_until
     * @return array
     */
    public function createBeneficiary(string $name, string $account, Carbon $valid_until) : array
    {
        try {
            $system = config('payment.system');
            $result = $this->client->post('api/beneficiary', ['form_params' => compact('name', 'system', 'account', 'valid_until')]);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
        return \GuzzleHttp\json_decode($result->getBody(), true);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getPayer() : array
    {
        try {
            $client = \Modules\OpenId\Repositories\Client::getClient();
            $result = $client->get('api/user');
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
        $user = (\GuzzleHttp\json_decode($result->getBody(), true))['user'];

        if (!isset($user['address'])) {
            throw new \Exception('Endereço não preenchido.', 422);
        }

        return [
            'name' => $user['name'],
            'address' => $user['address']['street'],
            'district' => $user['address']['district'],
            'cep' => $user['address']['zip'],
            'state' => $user['address']['state']['initials'],
            'city' => $user['address']['city'],
            'cpf' => $user['cpf']];
    }

    /**
     * @param array $descriptions
     * @param array $details
     * @param float $value
     * @param float $discount
     * @param string $beneficiary
     * @param int $deadline
     * @return array
     * @throws \Exception
     */
    public function createBoleto(array $descriptions,
                                 array $details,
                                 float $value,
                                 string $beneficiary,
                                 float $discount = 0.0,
                                 int $deadline = 1) : array
    {
        $payer = $this->getPayer();
        if (isset($payer['error'])) {
            return $payer;
        }
        foreach ($descriptions as $key => $description) {
            $descriptions[$key] = compact('description');
        }
        $data = compact('payer', 'descriptions', 'value', 'discount', 'details', 'deadline', 'beneficiary');
        try {
            $result = $this->client->post('api/boleto', ['form_params' => $data]);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return \GuzzleHttp\json_decode($result->getBody(), true);
    }

    /**
     * @param array $details
     * @param array $credit_card
     * @param float $value
     * @param string $beneficiary
     * @param float $discount
     * @return array
     * @throws \Exception
     */
    public function createCreditCard(array $details,
                                 array $credit_card,
                                 float $value,
                                 string $beneficiary,
                                 float $discount = 0.0) : array
    {
        $payer = $this->getPayer();
        if (isset($payer['error'])) {
            return $payer;
        }
        $data = compact('payer', 'credit_card', 'value', 'discount', 'details', 'beneficiary');
        try {
            $result = $this->client->post('api/credit', ['form_params' => $data]);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return \GuzzleHttp\json_decode($result->getBody(), true);
    }
}