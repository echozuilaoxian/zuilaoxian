<?php
require "../config.php";
$db_pdo=new mysqlpdo($mysql_config);
$pagecount=999;
use QL\QueryList;

$url="http://haha.sogou.com/video/new/".$page;
$datahtml = QueryList::get($url,null,[
	'cache' => $huan_path,
	'cache_ttl' => 60*60*12
	])
	->getHtml();


$rules=array(
	"id"=>array('','href'),
	"title"=>array('.tit','text'),
	"img"=>array('img','src')
);
$range='.container>ul>li>a';
if (!$datahtml){
	//失败了就从数据库读取
	$count=$db_pdo->counts("SELECT count(*) FROM haha where url is not null");
	$pagecount=ceil($count/$pagesize);//总页数
	if ($page>$pagecount){$page=$pagecount;}
	$zhizhen=$pagesize*($page-1);
	$result=$db_pdo->querylists("SELECT ids,img,title FROM haha where url is not null limit $zhizhen,$pagesize");
		if ($result){
			$str=array(
				"msg"=>true,
				"page"=>$page,
				"pagesize"=>$pagesize,
				"count"=>$count,
				"pagecount"=>$pagecount
			);
			foreach($result as $i =>$row){
				$str["list"][]=array(
				"id"=>$row["ids"],
				"title"=>$row["title"],
				"img"=>$row["img"]
				);
			}
		}else{
			$str=array(
				"msg"=>false
			);
		}
}else{
	//没有失败则进入提取
	$data = QueryList::html($datahtml)
	->rules($rules)
	->range($range)
	->queryData(
		function($x)use($api){
			$x['id']=str_replace("/","",$x['id']);
			$x['img']=$api->cutstr2($x['img'],"url=","");
			return $x;
		}
	);
		$str=array(
			"msg"=>true,
			"page"=>$page,
			"pagesize"=>$pagesize,
			"count"=>count($data),
			"pagecount"=>$pagecount
		);
	foreach($data as $i => $row){
		$id=$row['id'];
		$img=$row['img'];
		$title=$row['title'];
		$str["list"][]=array(
			"id"=>$id,
			"title"=>$title,
			"img"=>$img
		);
		$haha=$db_pdo->queryrow("SELECT * FROM haha where ids=".$id);
		if (!$haha){
			$db_pdo->execs("INSERT INTO haha (ids,img,title) VALUES (".$id.",'".$img."','".$title."')");
		}
	}
}

//输出
$html=$api->head("小视频");
$html.=<<<api
<pre>json页面:<a href="{$thisurl}web_charset=json{$query_string}">{$thisurl}web_charset=json{$query_string}</a></pre>
<div class="well well-sm">弹出层打开视频 <input type="checkbox" id="haha_new_box" name="haha_new_box" value="弹出层打开视频" checked="checked" /></div>
api;

if ($str["msg"]){
$html.=$api->page_z(0,$str['count'],$str['pagecount'],$str['pagesize'],$page,"?");
	foreach ($str["list"] as $i => $row){
$html.=<<<api

	<div class="media">
		<div class="media-left media-middle">
			<img src="{$row['img']}" class="media-object" style="width:200px">
		</div>
		<div class="media-body">
			<a id="haha" hid="{$row['id']}" data-loading-text="Loading..." type="button">
			<h4 class="media-heading">{$row['title']}</h4>
			</a>
		</div>
	</div>

api;
}
$html.=$api->page_z(1,$str['count'],$str['pagecount'],$str['pagesize'],$page,"?");

}else{
	$html.="内容出错";
}
$html.=$api->end();
echo $web_charset?$api->json($str):$html;