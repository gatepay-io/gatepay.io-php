# gatepay.io-php
gatepay.io php sdk

![home](https://gatepay.gatecdn.com/static/thedocs/1.3.1/assets/img/banner_admin.png)

这是一个个人收款系统Gatepay.io 提供的收款接口，如果你是一个开发者或者站长或者知识付费、内容创造的从业人员，对你会有帮助。

这个系统主要特点就是零费率和点对点即时到账到自己的收款微信或者支付宝上，不参与具体的交易细节。

--------------------——准备工作开始-----------------------------------

在使用这个SDK之前需要去gatepay.io 注册一个账户，然后在管理后台->账户配置里填写几个必填的选项。

1. 支付成功后回调地址, 作用是 如果收款成功了，会跳转到你预先设定的一个通知地址，如果是做一个会员充值的应用， 那么这个通知地址的作用就是给会员加钱。

2. 支付成功后前台跳转地址，作用是 如果收款成功了，这个页面要跳转到哪里去，如果是做一个会员充值的应用，那么这个前台跳转地址应该是跳转到会员中心的首页或者钱包的首页。

3. 用户支付后未到账（可能超时付款），支付页面显示的反馈按钮链接， 这个就是如果用户支付超时，则页面跳转到哪里去，这个可填可不填

![help_01](https://gatepay.gatecdn.com/assets/img/help/help_01.png)

4. 个人任意金额支付宝二维码, 手机支付宝->收钱->不填金额->保存图片，上传到后台就可以了。

5. 个人任意金额微信二维码，手机微信->我->支付->收付款->二维码收款->不填金额->保存图片，上传到后台就可以了。

6. 支付宝ID,手机支付宝扫描下面的二维码，填写到后台即可。

![help_02](https://gatepay.gatecdn.com/assets/img/help/help_02.png)

---------------------准备工作结束-------------------------------------


接下来我们讲讲这个sdk怎么耍起来，先是在你的应用的composer里新增这个sdk

```bash
composer require gatepay-io/gatepay.io-php
```

然后在入口的位置声明使用类

```php
  require_once './autoload.php';
  use \gatepayio\Api;
  $api = new Api();
```

现在进入正题，gatepay提供三种接口，分别是anypay，stablepay，grouppay，不论哪种接口，我们都需要使用gatepay后台提供我们的appkey和appsecret。

```php
$appkey = 'your appkey from gatepay';
$appsecret = 'your appsecret from gatepay';
```

1. 任意金额支付 anypay,

这个主要的特点是：金额是任意的，无需后台提前上传二维码，填写商品啥的。比如你的应用是不固定价格的服务，比如充值会员的服务，想充多少充多少。那这个比较合适搞。

调用也很简单：
```php
$price = 1.00; //要充值的金额，随便填，这里我写的1块钱
$out_order_id = uniqid(); //你的系统产生的订单号，这里演示 就是搞随机函数生成了一个单号
$custom = 'terry'; //这个字段是自定义的字段，比如要充值给你的网站哪个用户， 我这里填写的是充值给我的客户名叫terry的那个家伙。
$type = 'wechat'; //这个很明显了，支付方式，这里填的是微信， 如果是支付宝，填写alipay。
//组装参数
$params = [
  'price'=>$price,
  'type'=>$type,
  'out_order_id'=>$out_order_id,
  'custom'=>$custom,
];
//开始支付请求(分别是:授权->签名->组装生成连接->请求)
$response = $api->auth($appkey,$appsecret)->sign($params)->route('anypay','create')->request();
//做下判断，看看是否生成成功，
//print_r($response);
if($response && $response['code']==100){
  //生成支付连接成功了，
    $pay_url = $response['data']['pay_url'];//支付的连接
    //跳转到gatepay那里去支付
    header("location:".$pay_url);
}
else{
  //出了问题？
    if($response){
      echo $response['msg'];
    }
    else{
      echo '服务器开了点小差~~';
    }
}
```

2.固定商品支付 stablepay,

这个主要的特点是，先要去管理后台->产品卡密->产品管理里创建一个产品，然后上传支付宝微信二维码。

然后在管理后台->产品卡密->卡密管理 里导入这个产品的卡密。

感觉是很麻烦，但是这个stablepay 可以帮我们做到销售卡密的过程，比如你要卖什么xxx影视会员点卡之类，对接这个api就可以。

直接上代码：

```php
$product_id = 8; //产品的编号(ID)， 这个在管理后台->产品卡密->产品管理里可以看到。
$out_order_id = uniqid(); //你的系统产生的订单号，这里演示 就是搞随机函数生成了一个单号
$custom = 'terry'; //这个字段是自定义的字段，比如要充值给你的网站哪个用户， 我这里填写的是充值给我的客户名叫terry的那个家伙。
$type = 'wechat'; //这个很明显了，支付方式，这里填的是微信， 如果是支付宝，填写alipay。
//组装参数
$params = [
  'product_id'=>$product_id,
  'type'=>$type,
  'out_order_id'=>$out_order_id,
  'custom'=>$custom,
];
//开始支付请求(分别是:授权->签名->组装生成连接->请求)
$response = $api->auth($appkey,$appsecret)->sign($params)->route('stablepay','create')->request();
//做下判断，看看是否生成成功，
//print_r($response);
if($response && $response['code']==100){
  //生成支付连接成功了，
    $pay_url = $response['data']['pay_url'];//支付的连接
    //跳转到gatepay那里去支付
    header("location:".$pay_url);
}
else{
  //出了问题？
    if($response){
      echo $response['msg'];
    }
    else{
      echo '服务器开了点小差~~';
    }
}
```
3. 组合商品支付  grouppay,

可以实现对多个固定商品 组合后进行支付， 如果你想实现 一些组合出售的需求，可以使用这个接口。

直接上代码

```php
$fields = '8:1,7:2';//代表产品ID为8的购买1件，产品ID为7的购买2件 
$out_order_id = uniqid(); //你的系统产生的订单号，这里演示 就是搞随机函数生成了一个单号
$custom = 'terry'; //这个字段是自定义的字段，比如要充值给你的网站哪个用户， 我这里填写的是充值给我的客户名叫terry的那个家伙。
$type = 'wechat'; //这个很明显了，支付方式，这里填的是微信， 如果是支付宝，填写alipay。
//组装参数
$params = [
  'fields'=>$fields,
  'type'=>$type,
  'out_order_id'=>$out_order_id,
  'custom'=>$custom,
];
//开始支付请求(分别是:授权->签名->组装生成连接->请求)
$response = $api->auth($appkey,$appsecret)->sign($params)->route('grouppay','create')->request();
//做下判断，看看是否生成成功，
//print_r($response);
if($response && $response['code']==100){
  //生成支付连接成功了，
    $pay_url = $response['data']['pay_url'];//支付的连接
    //跳转到gatepay那里去支付
    header("location:".$pay_url);
}
else{
  //出了问题？
    if($response){
      echo $response['msg'];
    }
    else{
      echo '服务器开了点小差~~';
    }
}
```





