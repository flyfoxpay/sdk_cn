<?php
require_once("flyfoxpay_config/flyfoxpay.config.php");
function printf_info($data)
{
    foreach($data as $key=>$value){
                echo "<font color='#f00;'>";
		if($key=='status'){echo '状态';}
		elseif($key=='okpay'){echo '支付成功';}
		elseif($key=='nopay'){echo '未结帐';}
		elseif($key=='errorpay'){echo '支付成功但金额错误';}
		elseif($key=='msg'){echo '说明';}
		elseif($key=='money'){echo '金额';}
		elseif($key=='error'){echo '错误';}
		elseif($key=='time'){echo '建立时间';}
		elseif($key=='timeok'){echo '处理日期';}
		elseif($key=='idcode'){echo '交易序号';}
		elseif($key=='status_trade'){echo '交易状态';}
		elseif($key=='type'){echo '支付方式';}
		elseif($key=='fee'){echo '手续费';}
		elseif($key=='new_money'){echo '帐号内剩馀金额';}
		elseif($key=='id'){echo '提现ID';}
		else{echo $key;}
		echo "</font> : ".htmlspecialchars($value, ENT_QUOTES)." <br/>";
    }
}
function printf_infos($data)
{
	$fa=$data['number'];
    for ($x=0; $x<=$fa; $x++) {
		$dats=$data['list_withdraw'][$x];
    if($x==$fa){die;}else{
    foreach($dats as $key=>$value){
                echo "<font color='#f00;'>";
		if($key=='status'){echo '状态';}
		elseif($key=='okpay'){echo '支付成功';}
		elseif($key=='nopay'){echo '未结帐';}
		elseif($key=='errorpay'){echo '支付成功但金额错误';}
		elseif($key=='msg'){echo '说明';}
		elseif($key=='money'){echo '金额';}
		elseif($key=='error'){echo '错误';}
		elseif($key=='time'){echo '建立时间';}
		elseif($key=='timeok'){echo '处理日期';}
		elseif($key=='idcode'){echo '交易序号';}
		elseif($key=='status_trade'){echo '交易状态';}
		elseif($key=='type'){echo '支付方式';}
		elseif($key=='fee'){echo '手续费';}
		elseif($key=='new_money'){echo '帐号内剩馀金额';}
		elseif($key=='id'){echo '提现ID';}
		else{echo $key;}
		echo "</font> : ".$value." <br/>";
}	        echo '<HR>';
} 
}
}
function curl_post($url,$post)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($ch);
curl_close ($ch);
return $result;
}

$flyfoxpay = new flyfoxpay($flyfoxpay_config);
class flyfoxpay {
    function __construct($flyfoxpay_config){
	$this->flyfoxpay_config = $flyfoxpay_config;
	}
    function flyfoxpay($flyfoxpay_config) {
    	$this->__construct($flyfoxpay_config);
    }
    /**
     * 建立订单API
	 * @type如果未设定将直接选择全支付方式
     */
function addpay($trade_no, $trade_name, $amount, $type,$customize1,$customize2,$customize3) {
 $key=$this->flyfoxpay_config['key'];
 $id=$this->flyfoxpay_config['id'];
 $mail=$this->flyfoxpay_config['mail'];
 $return=$this->flyfoxpay_config['return'];
 $url = "https://sc-i.pw/api/";//API位置
 $post_value=array(
       "key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       "trade_no"=>$trade_no, //商家订单ID
       "amount"=>$amount, //订单金额(需大于50)
       "trade_name"=>$trade_name, //订单名称
       "type"=>$type, //指定付款方式，预设为all
       "return"=>$return, //支付完成返回网址
       "customize1"=>$customize1,//自订义1
       "customize2"=>$customize2,//自订义2
       "customize3"=>$customize3,//自订义3
                    ); 
 $output = curl_post($url,$post_value);
 $json=json_decode($output, true);

return array('status' => $json['status'], 'url' => $json['url'], 'error' => $json['error']);
}
/**
     * 检查订单API
     */
function check($trade_no,$trade_nos) {
 $key=$this->flyfoxpay_config['key'];
 $id=$this->flyfoxpay_config['id'];
 $mail=$this->flyfoxpay_config['mail'];
 $url = "https://sc-i.pw/api/check/";//API位置
 $post_value= array(
       "key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       "trade_no"=>$trade_no, //商家订单ID
       "trade_nos"=>$trade_nos, //支付号ID
                    ); 
   $output = curl_post($url,$post_value);
   $json=json_decode($output, true);
   $security1  = array();

   $security1['mchid']      = $id;//商家ID

   $security1['status']        = "7";//验证，请勿更改

   $security1['mail']      = $mail;//商家EMAIL

   $security1['trade_no']      = $json['trade_no'];//商家订单ID
	
   $o='';
     foreach ($security1 as $k=>$v)
  {
    $o.= "$k=".($v)."&";
  }

    $sign1 = md5(substr($o,0,-1).$key);//**********请替换成商家KEY
if($json['sign']==$sign1){
  $sHtml = array('status' => $json['status'],'status_trade' => $json['status_trade'],'msg' => '验证成功','type'=>$json['type'],'trade_no'=>$json['trade_no']);
}else{
  $sHtml = array('status' => $json['status'],'error' => $json['error'],'msg' => '验证失败');
}

return $sHtml;
}
/**
     * 查询订单数量API
     */
function check_order() {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/check_order/";//API位置
 
$post_value= array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       ); 
$output = curl_post($url,$post_value);

$json=json_decode($output, true);


return $json;
}
/**
     * 查询帐号馀额API
     */
function search() {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/search/";//API位置
$post_value = array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       ); 
$output = curl_post($url,$post_value);

$json=json_decode($output, true);


return $json;
}
/**
     * 提现列表API
     */
function list_withdraw() {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/list_withdraw/";//API位置
 $post_value= array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       ); 
$output = curl_post($url,$post_value);
$json=json_decode($output, true);


return $json;
}
/**
     * 提现状态查询API
     */
function check_withdraw($withdrawid) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/check_withdraw/";//API位置
 $post_value= array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
	   "withdrawid"=>$withdrawid,//提现ID
       ))); 
$output = curl_post($url,$post_value);
$json=json_decode($output, true);


return $json;
}
/**
     * 提现状态查询API
     */
function withdraw($type,$money,$bank,$bank_name,$bank_code) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/withdraw/";//API位置
$post_value= array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       "money"=>$money, //提现金额(依照"费率表-提现手续费"中为准)
       "type"=>$type, //提现方式(这里以支付宝为例)
       "alipay"=>$bank, //支付宝帐号
       "alipay_name"=>$bank_name, //支付宝帐号所有人名字
       //银行提现方式
       "bank"=>$bank, //银行帐号
       "bank_name"=>$bank_name, //银行帐号收款人名字
       //银行提现方式(台湾)
       "bank_code"=>$bank_code, //台湾地区银行代码
       ); 
$output = curl_post($url,$post_value);
$json=json_decode($output, true);


return $json;
}
/**
     * 取得新商家KEY API
     */
function rekey() {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/rekey/";//API位置
 
$post_value= array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       ); 
$output = curl_post($url,$post_value);

$json=json_decode($output, true);


return $json;
}
/**
     * 修改后台登入密码 API
     */
function passwd($passwdold,$passwdnew,$passwdnews) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/passwd/";//API位置
$post_value= array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
	   "passwdold"=>$passwdold, //旧密码
       "passwdnew"=>$passwdnew, //新密码
       "passwdnews"=>$passwdnews, //再次输入新密码
       ); 
$output = curl_post($url,$post_value);
$json=json_decode($output, true);


return $json;
}
/**
     * 修改后台登入密码 API
     */
function callback($notify_url) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/callback/";//API位置
$post_value= array("key"=>$key, //商家KEY
       "id"=>$id, //商家ID
       "mail"=>$mail, //商家EMAIL
       //二选一，同时存在已null优先
       "null"=>$notify_url, //清除现有notify_url(请固定填入1)
       "notify_url"=>$notify_url, //callback网址(https://申请时的网址/路径)
       ); 
$output = curl_post($url,$post_value);
$json=json_decode($output, true);


return $json;
}
/**
     * 取消订单API
     */
function cancel_order($trade_no) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/cancel_order/";//API位置
$post_value= array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       "trade_no"=>$trade_no, //商户订单ID
       ); 
$output = curl_post($url,$post_value);
$json=json_decode($output, true);


return $json;
}
	/**
     * 取消提现申请API
     */
function cancel_withdraw($no) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/cancel_withdraw/";//API位置
$post_value= array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       "no"=>$no, //商户订单ID
       ); 
$output = curl_post($url,$post_value);
$json=json_decode($output, true);


return $json;
}
}
?>
