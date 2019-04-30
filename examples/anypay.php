<?php
require_once '../src/init.php';
use gatepayio\Api;
/*
  Gatepay SDK 任意金额支付演示 
*/
$appkey = 'your appkey';
$appsecret = 'your appsecret';
$response = $api->auth($appkey,$appsecret)->sign([
	'price'=>$price,
	'type'=>$type,
	'out_order_id'=>$out_order_id,
	'custom'=>$custom,
])->route('anypay','create')->request();
var_dump($response);
?>
