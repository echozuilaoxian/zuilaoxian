<?php
require_once("../config.php");
$bookid=isset($_GET['bookid'])?$_GET['bookid']:NULL;
$viewid=isset($_GET['viewid'])?$_GET['viewid']:NULL;
if (!$bookid || !$viewid){exit($api->head('错误').$api->msg('错误,请重试','danger').$api->end());}
use QL\QueryList;
$url="https://www.9txs.com/book/{$bookid}/{$viewid}.html";
$datahtml = QueryList::get($url,null,[
	'cache' => $huan_path,
	'cache_ttl' => 60*60*48
	])
	->getHtml();
/*获取书本章节信息*/
$rules=array(
	"book"=>array('.light>#bookname','text'),
	"title"=>array('h1','text'),
	"content"=>array('#content','html','-p:first'),
	"up"=>array('.page>a:eq(0)','href'),
	"down"=>array('.page>a:eq(2)','href')
);
$range='.area';
$data = QueryList::html($datahtml)
->rules($rules)
->range($range)
->queryData(
	function($x){
		$x['up']=explode("/",$x['up'])[3];
		if ($x['up']){$x['up']=explode(".",$x['up'])[0];}
		$x['down']=explode("/",$x['down'])[3];
		if ($x['down']){$x['down']=explode(".",$x['down'])[0];}
		return $x;
	}
)[0];
$booktitle=$data['book'];
$viewtitle=$data['title'];
$content=$data['content'];
$content=str_replace('<p>',"<p>&emsp;&emsp;",$content);
$up=$data['up'];
$down=$data['down'];
$title=$viewtitle." ".$booktitle;
$pager="<li class=\"list-group-item\">";
$pager.="<ul class=\"pager\">";
if ($up){
	$pager.="<li class=\"previous\"><a href=\"view.php?bookid=".$bookid."&viewid=".$up."\">&larr; 上一章</a></li>";
}else{
	$pager.="<li class=\"previous disabled\"><span>&larr; 上一章</span></li>";
}
$pager.="<li><a href=\"book.php?bookid=".$bookid."\">章节目录</a></li>";
if ($down){
	$pager.="<li class=\"next\"><a href=\"view.php?bookid=".$bookid."&viewid=".$down."\">下一章 &rarr;</a></li>";
}else{
	$pager.="<li class=\"next disabled\"><span>下一章 &rarr;</span></li>";
}
$pager.="</ul></li>";
$html.='
		<ul class="breadcrumb">
			<li><a href="./?">小说首页</a></li>
			<li><a href="book.php?bookid='.$bookid.'">'.$booktitle.'</a></li>
		</ul>
	<li class="list-group-item"><h3>'.$viewtitle.'</h3></li>
'.$pager.'
<li class="list-group-item">'.$content.'</li>
'.$pager.'
';PHP_EOL;
$apistr['msg']=['book'=>$booktitle,'title'=>$viewtitle,'content'=>$content,'next'=>$down,'prev'=>$up];
$html=$api->head($title).$html.$api->end();
echo $web_charset?$api->json($apistr):$html;
fastcgi_finish_request();
if ($down){
	$url2="https://www.9txs.com/book/{$bookid}/{$down}.html";
	$datahtml2 = QueryList::get($url2,null,[
		'cache' => $huan_path,
		'cache_ttl' => 60*60*48
		])
		->getHtml();
}