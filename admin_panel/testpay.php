<?php include('../config/config.php'); 
include_once "../config/common.php";
$dbConn = establishcon();

$currency = "nzd";
$routing_number = '';

$STRIPE_API_KEY = "sk_test_51JY0pmEKo3c1uRhOT0T2Gah6VRc96ExX2vjAotRa6ir4EUEt77GQRKALUHbOlF4t5eFn4eCgtcabMjnjyJSPMB5B00tNBmI9L0";

require_once '../stripe/init.php';

/*$stripe = new \Stripe\StripeClient(
  $STRIPE_API_KEY
);*/

\Stripe\Stripe::setApiKey($STRIPE_API_KEY);
$customer = \Stripe\Customer::create(
["email" => "jenny.rosen@example.com"],
["stripe_account" => "acct_1JzELF2QPsa7MKE4"]
);

$res = $customer->jsonSerialize();
echo "<pre>";
print_r($res);
die;

/*$getprovider = dbQuery($dbConn, "SELECT id,name,email,currency,country,accno,routing_no,stripe_account from users where id = '14'");
$provider = dbFetchArray($getprovider);

$account = $stripe->accounts->create([
  'type' => 'custom',
  'country' => $provider['country'],
  'email' => $provider['email'],
  'capabilities' => [
    'card_payments' => ['requested' => true],
    'transfers' => ['requested' => true],
  ],
  'external_account' => [
    'object' => 'bank_account',
    'country' => $provider['country'],
    'currency' => $provider['currency'],
    'account_holder_name' => $provider['name'],
    'account_holder_type' => 'individual',
    'routing_number' => $provider['routing_no'],
    'account_number' => $provider['accno']
    ],
  ]);

  echo $account->id;*/

/*\Stripe\Stripe::setApiKey($STRIPE_API_KEY);
$transfer = \Stripe\Transfer::create([
  'amount' => 5,
  'currency' => 'nzd',
  'destination' => 'default_for_currency'
], [
  'stripe_account' => '{acct_1JzELF2QPsa7MKE4}',
]);

$chargeJson = $transfer->jsonSerialize();
echo "<pre>";
print_r($chargeJson);*/
