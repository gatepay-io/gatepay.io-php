<?php
namespace gatepayio;
use Hprose\Client;
class Api{
	private $client;//客户端
	private $path;//请求根地址
	private $url;//请求url
	private $method;//请求方法
	private $appkey;//秘钥
	private $appsecret;//密匙
	private $sign;//签名
	private $params;//全部参数
	public function __construct($path="https://gatepay.io/api"){
		$this->path = $path;
	}

	public function auth($appkey,$appsecret){
		return $this->appkey($appkey)->appsecret($appsecret);
	}

	public function appkey($appkey){
		$this->appkey = $appkey;
		return $this;
	}

	public function appsecret($appsecret){
		$this->appsecret = $appsecret;
		return $this;
	}

	public function sign($params=[]){
		$keys = empty($params) ? '' : implode('',array_values($params));
		$this->sign = md5(md5($this->appkey.$keys).$this->appsecret);
		$this->params  = $params;
		$this->params['appkey'] = $this->appkey;
		$this->params['sign'] = $this->sign;
		return $this;
	}

	public function dump(){
		var_dump($this->params);
	}


	public function make(){
		$method = explode('_',$this->method);
		$method = $method[1];
		$url = $this->url.$method.'?';
		foreach($this->params as $key=>$value){
			$url.= $key.'='.$value.'&';
		}
		$url.='_t='.time();
		return $url;
	}
	
	public function route($controller,$method){
		$this->method = $controller.'_'.$method;
		$this->url = $this->path.'/'.$controller.'/';
		return $this;
	}
	public function request(){
		$this->client = Client::create($this->url,false);
		$method = $this->method;
		return $this->client->$method($this->params);
	}
}
