<?php
require "../config.php";
use GuzzleHttp\Psr7\Response;
use QL\QueryList;
$id=$_GET['id']??NULL;
if (!$id){exit($api->msg("id错误","id错误","danger"));}
$url="https://www.tupianzj.com/".base64_decode($id);
	$datahtml = QueryList::get($url,null,[
		'cache' => $huan_path,
		'cache_ttl' => 60*60*12
		])
	->getHtml();
	$rules=array(
		"img"=>array('#bigpic img','src'),
		"pages"=>array('.pages li:eq(0)','text'),
		"title"=>array('h1:last','text')
	);
	$range='';
	$data = QueryList::html($datahtml)
		->rules($rules)
		->range($range)
		->encoding('UTF-8')
		->removeHead()
		->queryData()[0];
	$title=$data['title'];
	$page=$api->cutstr($data['pages'],'共','页');
	$img[]=$data['img'];
	for ($i=2;$i<=$page;$i++){
		$urls[]=str_replace('.html','_'.$i.'.html',$url);
	}
	$rules=array(
		"img"=>array('#bigpic img','src')
	);
	$qldata=QueryList::rules($rules)
		->range($range)
		->multiGet($urls)
		// 设置并发数为2
		->concurrency(2)
		// 设置GuzzleHttp的一些其他选项
		->withOptions([
			'timeout' => 60
		])
		// 设置HTTP Header
		->withHeaders([
			'User-Agent' => 'QueryList'
		])
		// HTTP success回调函数
		->success(function (QueryList $ql, Response $response, $index){
			global $img;
			$img[]= $ql->queryData()[0]['img'];
		})
		// HTTP error回调函数
		->error(function (QueryList $ql, $reason, $index){
			// ...
		})
		->send();


	$html.=$api->head($title);
	$html.='
			<ul class="breadcrumb">
				<li><a href="/">网站首页</a></li>
				<li><a href="./">美女图片</a></li>
				<li>'.$title.'</li>
			</ul>
	';
	$html.="<h3>{$title}</h3>\n<hr>\n";

	$html.='
		<li class="list-group-item">
	';		
		foreach($img as $row){
			if ($row){
				if (stripos($row,"http://")!==0){$row="{$row}";}
				$html.='
				<img alt="" src="'.$row.'" style=\"width:100%;max-width:400px;>
				';
			}
		}
	$html.='
		</li>
	';
$apistr['msg']=['title'=>$title,'count'=>$page];
$apistr['img']=$img;
echo $web_charset?$api->json($apistr):$html.$api->end();