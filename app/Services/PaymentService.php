<?php


namespace App\Services;


class PaymentService
{
    public function index($request)
    {
        $sslcommerz = new SSLCommerze();
        $sslcommerz->setPaymentDisplayType('hosted'); // enum('hosted', 'checkout')
        $sslcommerz->setTotalAmount($request->input('amount'));
        $sslcommerz->setCurrency('BDT');
        $sslcommerz->setTranId(uniqid()); // set your transaction id here

        $sslcommerz->setSuccessUrl(route('payment.ssl.callback', [ 'success', 'user_id' => $request->header('id') ]));
        $sslcommerz->setFailUrl(route('payment.ssl.callback', [ 'failed', 'user_id' => $request->header('id') ]));
        $sslcommerz->setCancelUrl(route('payment.ssl.callback', [ 'cancelled', 'user_id' => $request->header('id') ]));

        $sslcommerz->setCustomerName($request->name);
        $sslcommerz->setCustomerEmail($request->email);
        $sslcommerz->setCustomerPhone($request->phone);
        $sslcommerz->setCustomerAddress1('SAR Bhaban, Ka-78 Pragati Sarani Main Road, 1229');
        $sslcommerz->setCustomerCountry('Bangladesh');
        $sslcommerz->setCustomerCity('Dhaka');
        $sslcommerz->setCustomerPostCode('1229');
        $sslcommerz->setCustomerState('Dhaka');

        $sslcommerz->setShippingMethod('NO');
        $sslcommerz->setStorePassword('funne644e48db5ede2@ssl');
        $sslcommerz->setEmiOption(0); // enum(1, 0)
        $sslcommerz->setProductName('Subscription');
        $sslcommerz->setProductCategory('Package');
        $sslcommerz->setProductProfile('Funnelliner-Subscription');

        $response = $sslcommerz->initPayment($sslcommerz);

        return data_get($response,'GatewayPageURL');
    }
}
