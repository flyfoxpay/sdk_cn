<?php
require_once("flyfoxpay_config/flyfoxpay.config.php");
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#f00;'>";
		if($key=='status'){echo '状态';}
		elseif($key=='okpay'){echo '支付成功';}
		elseif($key=='nopay'){echo '未结账';}
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
		elseif($key=='new_money'){echo '账号內剩余金额';}
		elseif($key=='id'){echo '提现ID';}
		else{echo $key;}
		echo "</font> : ".htmlspecialchars($value, ENT_QUOTES)." <br/>";
    }
}function printf_infos($data)
{
	$fa=$data['number'];
    for ($x=0; $x<=$fa; $x++) {
		
					$dats=$data['list_withdraw'][$x];
		if($x==$fa){die;}else{
  foreach($dats as $key=>$value){
        echo "<font color='#f00;'>";
		if($key=='status'){echo '状态';}
		elseif($key=='okpay'){echo '支付成功';}
		elseif($key=='nopay'){echo '未结账';}
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
		elseif($key=='new_money'){echo '账号內剩余金额';}
		elseif($key=='id'){echo '提现ID';}
		else{echo $key;}
		echo "</font> : ".$value." <br/>";
    }	echo '<HR>';
} }
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
	 * @type如果未设置將直接选择全支付方式
     */
	function addpay($trade_no, $amount, $trade_name, $type) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$return=$this->flyfoxpay_config['return'];
$url = "https://sc-i.pw/api/";//API位置
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       "trade_no"=>$trade_no, //商户订单ID
       "amount"=>$amount, //订单金额(需大于50)
       "trade_name"=>$trade_name, //订单名称
	   "type"=>$type, //指定付款方式，预设为all
       "return"=>$return, //支付完成返回网址
      ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","url":"https://sc-i.pw/pay/?sign=*****"}
//重复订单
{"status":"204","error":"重复订单內容","url":"https://sc-i.pw/pay/?sign=*****"}
//重复订单ID(trade_no相同)
{"status":"206","error":"重复订单ID"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"315","error":"请检查TYPE栏位是否错误"}
{"status":"406","error":"金额不可低于50"}
*/ 
$json=json_decode($output, true);

return array('status' => $json['status'], 'url' => $json['url'], 'error' => $json['error']);
}
/**
     * 检查订单API
     */
function check($trade_no) {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/check/";//API位置
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       "trade_no"=>$trade_no, //商户订单ID
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","status_trade":"noapy","sign":"90e5f1f7ef87cd2e43729ba4378656b5"}
{"status":"200","trade_no":"1278217527512","type":"o_alipay","status_trade":"payok","sign":"*****"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"416","error":"请检查订单ID是否有误"}
*/ 
$security1  = array();

$security1['mchid']      = $id;//商户ID

$security1['status']        = "7";//验证，请勿更改

$security1['mail']      = $mail;//商户EMAIL

$security1['trade_no']      = $trade_no;//商户订单ID

foreach ($security1 as $k=>$v)

{

    $o.= "$k=".($v)."&";

}

$sign1 = md5(substr($o,0,-1).$key);//**********请替換成商户KEY
$json=json_decode($output, true);
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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","okpay":"****","nopay":"****","errorpay":"****","msg":"订单数量:****"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
*/ 

$json=json_decode($output, true);


return $json;
}
/**
     * 查询账号余额API
     */
function search() {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/search/";//API位置
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","msg":"你的账戶余额为******","money":"******"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
*/ 


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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","msg":"你的账戶余额为******","money":"******"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
*/ 


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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
	   "withdrawid"=>$withdrawid,//提现ID
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","msg":"已处理完成","time":"9999-99-99 99:99:99","timeok":"9999-99-99","idcode":"***********"}
{"id":"***","status":"4229","error":"找不到此提现ID资料，请联系客服"}
{"id":"***","status":"204","msg":"等待处理中，如超过24小时请联系客服","time":"9999-99-99 00:00:00"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"422","error":"请检查提现ID是否有误"}
*/ 


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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
	   "money"=>$money, //提现金额(按照"费率表-提现手续费"中为准)
       "type"=>$type, //提现方式(这里以支付宝为例)
       "alipay"=>$bank, //支付宝账号
       "alipay_name"=>$bank_name, //支付宝账号所有人名字
       //银行提现方式
       "bank"=>$bank, //银行账号
       "bank_name"=>$bank_name, //银行账号收款人名字
       //银行提现方式(台湾)
       "bank_code"=>$bank_code, //台湾省银行代码
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","msg":"已成功申请提现","type":"**********","money":"**********","fee":"***","new_money":"**********","time":"9999-99-99 99:99:99","id":"****"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"422","error":"请检查提现ID是否有误"}
{"status":"444","error":"请检查***或***是否为空"}
{"status":"447","error":"提现金额不可低于$NT***"}
{"status":"436","error":"请检查提现方式是否支持","type":"***"}
{"status":"2407","error":"请检查余额扣除手续费***元后是否足够金额提现","new_money":"********"}
*/  


$json=json_decode($output, true);


return $json;
}
/**
     * 取得新商户KEY API
     */
function rekey() {
$key=$this->flyfoxpay_config['key'];
$id=$this->flyfoxpay_config['id'];
$mail=$this->flyfoxpay_config['mail'];
$url = "https://sc-i.pw/api/rekey/";//API位置
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","msg":"已成功申请更改商户KEY","newkey":"*********"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
*/ 


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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
	   "passwdold"=>$passwdold, //旧密码
       "passwdnew"=>$passwdnew, //新密码
       "passwdnews"=>$passwdnews, //再次输入新密码
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","msg":"密码更新成功"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"412","error":"新密码两次输入资料不相同"}
{"status":"407","error":"旧密码错误"}
*/ 


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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       //二选一，同时存在已null优先
	   "null"=>$notify_url, //清除现有notify_url(请固定填入1)
       "notify_url"=>$notify_url, //callback网址(https://申请时的网址/路径)
	   
       
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","msg":"设置callback成功"}
{"status":"200","msg":"清除callback成功"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"452","error":"请检查网址格式是否正確"}
{"status":"479","error":"不是授权网址"}
*/ 


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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       "trade_no"=>$trade_no, //商户订单ID
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","status_trade":"cancel","msg";"成功取消订单"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"416","error":"请检查订单ID是否有误"}
*/ 

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
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
 array("key"=>$key, //商户KEY
       "id"=>$id, //商户ID
       "mail"=>$mail, //商户EMAIL
       "no"=>$no, //商户订单ID
       ))); 
$output = curl_exec($ch); 
curl_close($ch);
/*
回传格式:
//成功
{"status":"200","status_trade":"cancel","msg";"成功取消提现，钱并退回你的帐户","new_money":"*******"}
{"status":"200","error":"已取消提现无须再次取消"}
//以下为错误项目
{"status":"404","error":"未设置KEY或是ID或MAIL"}
{"status":"400","error":"请检查ID或是KEY或MAIL是否有误"}
{"status":"416","error":"请检查订单ID是否有误"}
{"status":"448","error":"无法取消此提现"}
{"status":"205","error":"提现资料异常请联系，wartw@jumpsky.taipei"}
{"status":"478","error":"取消提现失败，请在是一次或是连系wartw@jumpsky.taipei"}
{"status":"479","error":"取消提现失败，请在是一次或是连系wartw@jumpsky.taipei"}
*/ 

$json=json_decode($output, true);


return $json;
}
}
?>