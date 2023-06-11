<?php /** @noinspection PhpUndefinedFieldInspection */

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
    public function pay(Request $request): string
    {
        return $this->paymentService->index($request);
    }

    public function callback(Request $request, $status): RedirectResponse
    {
        if ($status !== 'success' && $request->data['order_type'] === 'plugin') {
            return Redirect::away(config('services.frontend_url.local').'plug-in');
        }

        if ($status !== 'success' && $request->data['order_type'] === 'package') {
            return Redirect::away(config('services.frontend_url.local').'subscription');
        }

        if ($status !== 'success' && $request->data['order_type'] === 'sms') {
            return Redirect::away(config('services.frontend_url.local').'bulk-sms');
        }

        $data = SSLCommerze::validate($request->val_id)->toArray();

        if($status === 'success' && $request->data['order_type'] === 'plugin') {

        }

        if($status === 'success' && $request->data['order_type'] === 'sms') {

        }

        if($status === 'success' && $request->data['order_type'] === 'package') {
            $res = Http::withHeaders(['X-Requested-With' =>'XMLHttpRequest'])->post(config('services.payment.local'), ['data' => $data, 'user_id' => $request->user_id]);
            return Redirect::away(config('services.frontend_url.production').'subscription?trxid='.$data['tran_id']);
        }
    }
}
