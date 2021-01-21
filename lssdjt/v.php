<?php
require '../config.php';
$id=$_GET['id']??NULL;
$db = new DBUtils();
$db -> instance('../db/lssdjt.db');
$ha_=$db->queryRow("SELECT * FROM [Content] where Id=".$id);
if ($ha_){
		$str=array(
		"msg"=>true,
		"id"=>$id,
		"title"=>$ha_['标题'],
		"content"=>$ha_['内容']
		);
}else{
	$str=array("msg"=>false);
}

$html="";
if ($str["msg"]){
	$html.=$api->head($str["title"]);
	$html.='
	<style>
		.content img{max-width:100%;}
	</style>
	<h3 class="list-group-item title">'.$str["title"].'</h3>
	<li class="list-group-item content">
	'.$str["content"].'
	</li>
	';
}
echo $web_charset?$api->json($str):$html.$api->end();