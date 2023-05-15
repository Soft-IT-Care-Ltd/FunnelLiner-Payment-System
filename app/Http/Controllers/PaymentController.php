<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Services\SSLCommerze;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

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
        return $response = $this->paymentService->index($request);
    }

    public function callback(Request $request, $status): RedirectResponse
    {
        if ($status !== 'success') {
            return Redirect::away(env('FRONTEND_URL').'subscription');
        }

        $data = SSLCommerze::validate($request->input('val_id'));
        $client = Http::withHeaders(['X-Requested-With' =>'XMLHttpRequest'])->post(config('services.payment.production'), ['subscription' => $data, 'user_id' => $request->user_id]);

        return Redirect::away(env('FRONTEND_URL').'subscription?trxid='.$data->tran_id);
    }
}
