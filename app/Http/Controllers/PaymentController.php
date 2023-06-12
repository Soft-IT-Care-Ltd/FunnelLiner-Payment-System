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
        if ($status !== 'success') {
            if ($request->data['order_type'] === 'plugin') {
                return Redirect::away(config('services.frontend_url.local') . 'plug-in');
            } elseif ($request->data['order_type'] === 'package') {
                return Redirect::away(config('services.frontend_url.local') . 'subscription');
            } elseif ($request->data['order_type'] === 'sms') {
                return Redirect::away(config('services.frontend_url.local') . 'bulk-sms');
            }
        } else {
            $data = SSLCommerze::validate($request->val_id)->toArray();
            $data['params'] = $request->data;

            $location = "";
            switch ($request->data['order_type']) {
                case  'plugin':
                    $location = 'plug-in';
                    break;
                case  'sms':
                    $location = 'bulk-sms';
                    break;
                default:
                    $location = 'subscription';
                    break;

            }
            $res = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])->post(config('services.payment.local'), ['data' => $data, 'user_id' => $request->user_id]);

            return Redirect::away(config('services.frontend_url.local') . $location.'?trxid=' . $data['tran_id']);
        }


    }
}
