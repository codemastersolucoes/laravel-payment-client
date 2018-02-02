<?php


namespace Payments\Client\Service;


use Carbon\Carbon;
use Payments\Client\Entities\Beneficiary;
use Payments\Client\Entities\Boleto;
use Payments\Client\Entities\CreditCard;


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
     * @param Beneficiary $beneficiary
     * @return array
     */
    public function createBeneficiary(Beneficiary $beneficiary) : array
    {
        try {
            $result = $this->client->post('api/beneficiary', ['form_params' => $beneficiary->jsonSerialize()]);
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
            $user = (\GuzzleHttp\json_decode($result->getBody(), true))['user'];
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),
                $exception->getCode() === 0 ? 500 : $exception->getCode());
        }
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
     * @param \JsonSerializable $payment
     * @return array
     * @throws \Exception
     */
    public function send(\JsonSerializable $payment) : array
    {
        $payer = $this->getPayer();
        $form_params = array_merge($payer, $payment->jsonSerialize());
        $uri = '';
        if ($payment instanceof Boleto) {
            $uri = 'api/boleto';
        } elseif ($payment instanceof CreditCard) {
            $uri = 'api/credit';
        } else {
            throw new \Exception('Tipo não reconhecido.', 500);
        }
        $result = $this->client->post($uri, compact('form_params'));

        return json_decode($result->getBody(), true);
    }
}