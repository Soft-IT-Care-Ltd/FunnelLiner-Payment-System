<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

/**
 * @property PaymentService $paymentService
 */
class PaymentController extends Controller
{
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function pay(Request $request)
    {
        return $this->paymentService->index($request);
    }
}
