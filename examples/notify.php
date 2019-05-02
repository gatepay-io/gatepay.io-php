<?php
/*
*   这个脚本用来接收通知回调
*   @author terry
*   @copyright gatepay.io
*/
//1.接收参数
$appkey = $_REQUEST['appkey'];//秘钥
$order_id = $_REQUEST['order_id'];//订单号
$out_order_id = $_REQUEST['out_order_id'];//外部订单号，既你的系统订单号
$price = $_REQUEST['price'];//商品价格
$realprice = $_REQUEST['realprice'];//真实支付价格
$type = $_REQUEST['type'];//支付类型，wechat 微信 alipay支付宝
$paytime = $_REQUEST['paytime'];//支付的时间戳，
$extend = $_REQUEST['extend'];//扩展自定义字段，支付时传递的，比如手机号，用户名之类的。
$sign = $_REQUEST['sign'];//签名数据

$appsecret = '这里是你的私钥';//在gatepay.io的管理后台->账户配置获取，妥善保管，不要泄露
//2.校验签名
$signed = md5(md5($appkey.$order_id.$out_order_id.$price.$realprice.$type.$paytime.$extend).$appsecret);
if($signed != $sign){
    //签名失败，直接返回错误给gatepay
    echo 'fail';exit;
}
//3.校验参数
//校验price，paytime 等等字段，判断是否受理

//4.业务处理
//根据extend字段，price字段，out_order_id字段 来处理自己的系统业务
//比如给网站会员充值，等等
//伪代码
/*
      if($order=$database->checkOrder($out_order_id)){
          if($user = $database->checkUser($extend)){
              //给用户充钱
                $user['money'] + = $price;
                 if($database->saveUser($user)){
                    echo 'success';exit;
                 }
                 echo 'fail';exit;
          }
          echo 'fail';exit;
      }
      echo 'fail';exit;
*/
  

//5.状态返回

//如果处理成功，则返回success 给 gatepay，如果处理失败，返回fail 给gatepay

//成功
//echo 'success'; exit;
//失败
//echo 'fail';exit;


