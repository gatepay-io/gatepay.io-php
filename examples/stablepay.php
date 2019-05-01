<?php
require_once '../src/init.php';
use gatepayio\Api;
/*
  Gatepay SDK 任意金额支付演示 
*/
$appkey = 'your appkey';
$appsecret = 'your appsecret';
$price = 0.01;
$type = 'wechat';
$custom = 'your custom info';
$out_order_id = uniqid();//your order system id
$product_id = 'your product_id in gatepay system';
$response = $api->auth($appkey,$appsecret)->sign([
	'price'=>$price,
	'type'=>$type,
	'out_order_id'=>$out_order_id,
	'custom'=>$custom,
  'product_id'=>$product_id,
])->route('stablepay','create')->request();
var_dump($response);
?>
