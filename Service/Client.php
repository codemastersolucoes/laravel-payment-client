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
            $result = $this->client->post('api/beneficiary', ['body' => compact('name', 'system', 'account', 'valid_until')]);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
        return \GuzzleHttp\json_decode($result->getBody(), true);
    }

    /**
     * @return array
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

        return [
            'name' => $user['name'],
            'address' => $user['street'],
            'district' => $user['district'],
            'cep' => $user['zip'],
            'state' => $user['state'],
            'city' => $user['city'],
            'cpf' => $user['cpf']];
    }

    /**
     * @param array $data
     * @return array
     */
    public function createBoleto(array $data) : array
    {
        $payer = $this->getPayer();
        if (isset($payer['error'])) {
            return $payer;
        }
        try {
            $result = $this->client->post('api/boleto', ['body' => array_merge(compact('payer'), $data)]);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return \GuzzleHttp\json_decode($result->getBody(), true);
    }
}