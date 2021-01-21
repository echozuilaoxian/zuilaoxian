<?php
require_once("../config.php");
$qq=isset($_POST['send_data'])?$_POST['send_data']:NULL;
use \Curl\Curl;
$curl = new Curl();

$url="https://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?g_tk=1518561325&uins={$qq}";
$data=$curl->get($url);
$data=iconv("GB2312","UTF-8",$data);
$pattern = '/portraitCallBack\((.*)\)/is';
preg_match($pattern,$data,$result);
$result=$result[1];
$nickname = json_decode($result, true)[$qq][6];

$html="QQ昵称：{$nickname}<br/>";
$html.="<img src=\"//q.qlogo.cn/headimg_dl?bs=qq&dst_uin={$qq}&spec=100\"><br/>";
$html.="<img src=\"//q.qlogo.cn/headimg_dl?bs=qq&dst_uin={$qq}&spec=0\" style=\"max-width:100%\"><br/>";

$str=array(
	"code"=>0,
	"title"=>$nickname.$qq,
	"html"=>$html
);
echo $api->json($str);