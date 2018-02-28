<?php

namespace Payments\Client\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Payments\Client\Http\Requests\PaymentRequest;

class ReturnController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param PaymentRequest $request
     * @return mixed
     */
    public function receive(PaymentRequest $request)
    {
        $class = '\\'.ltrim(config('payment.controller'), '\\');
        $controller = resolve($class);
        $action = config('payment.action');
        $payments = $request->get('payments', []);

        return $controller->$action($payments);
    }
}