<?php
require_once '../src/init.php';
use gatepayio\Api;
/*
  Gatepay SDK 组合商品支付
*/
$appkey = 'your appkey';
$appsecret = 'your appsecret';
$fields = 'product_id1:order_num1,product_id2:order_num2,....';
$type = 'wechat';
$custom = 'your custom info';
$out_order_id = uniqid();//your order system id
$response = $api->auth($appkey,$appsecret)->sign([
	'price'=>$price,
	'type'=>$type,
	'out_order_id'=>$out_order_id,
	'custom'=>$custom,
  'product_id'=>$product_id,
])->route('grouppay','create')->request();
var_dump($response);
?>
