<?php
namespace App\Services;

class PaymentService
{
    public function index($request): string
    {
        $sslcommerz = new SSLCommerze();
        $sslcommerz->setTotalAmount($request->input('amount'));
        $sslcommerz->setTranId(uniqid()); // set your transaction id here

        $sslcommerz->setSuccessUrl(route('payment.ssl.callback', [ 'success', 'user_id' => $request->header('id'), 'data' => $request->all() ]));
        $sslcommerz->setFailUrl(route('payment.ssl.callback', [ 'failed', 'user_id' => $request->header('id'), 'data' => $request->all() ]));
        $sslcommerz->setCancelUrl(route('payment.ssl.callback', [ 'cancelled', 'user_id' => $request->header('id'), 'data' => $request->all() ]));

        $sslcommerz->setCustomerName($request->name);
        $sslcommerz->setCustomerEmail($request->email);
        $sslcommerz->setCustomerPhone($request->phone);
        $sslcommerz->setCustomerAddress1('SAR Bhaban, Ka-78 Pragati Sarani Main Road, 1229');

        $sslcommerz->setCustomerCountry('Bangladesh');
        $sslcommerz->setCustomerCity('Dhaka');
        $sslcommerz->setCustomerPostCode('1229');
        $sslcommerz->setCustomerState('Dhaka');

        $sslcommerz->setShippingMethod('NO');
//        $sslcommerz->setStoreId('softitcareorderlive');
//        $sslcommerz->setStorePassword('5FFB140D5F63088778');

        $sslcommerz->setStoreId('funne644e48db5ede2');
        $sslcommerz->setStorePassword('funne644e48db5ede2@ssl');

        $sslcommerz->setProductionMode(false);
        $sslcommerz->setEmiOption(0); // enum(1, 0)
        $sslcommerz->setProductName(ucfirst($request->order_type));
        $sslcommerz->setProductCategory(ucfirst($request->order_type));
        $sslcommerz->setProductProfile('Funnelliner-'.$request->order_type);
        $response = $sslcommerz->initPayment($sslcommerz);

        return data_get($response,'GatewayPageURL');
    }
}
