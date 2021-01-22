<?php
require_once '../config.php';
use QL\QueryList;
	$type=[
		["list"=>"xiezhen","id"=>179,"name"=>"清纯美女"],
		["list"=>"xinggan","id"=>176,"name"=>"性感美女"],
		["list"=>"guzhuang","id"=>177,"name"=>"古装美女"],
		["list"=>"yishu","id"=>178,"name"=>"人体艺术"],
		["list"=>"siwa","id"=>193,"name"=>"丝袜美女"],
		["list"=>"chemo","id"=>194,"name"=>"香车美人"]
	];
	$list=$_GET['list']??"xiezhen";
	$listid=$_GET['listid']??179;
	$url="https://www.tupianzj.com/meinv/{$list}/list_{$listid}_{$page}.html";
	$datahtml = QueryList::get($url,null,[
		'cache' => $huan_path,
		'cache_ttl' => 60*60*12
		])
		->getHtml();
	$rules=array(
		"img"=>array('img','src'),
		"id"=>array('','href'),
		"title"=>array('','title')
	);
	$range='.list_con_box_ul>li>a';
	$data = QueryList::html($datahtml)
		->rules($rules)
		->range($range)
		->encoding('UTF-8','GB2312')
		->removeHead()
		->queryData();
	$page_count=QueryList::html($datahtml)->find(".pageinfo>strong:eq(0)")->text();
	$type_h="<ul class=\"breadcrumb\">\n";
	$title='';
	foreach ($type as $index){
		$apistr['type'][]=["list"=>$index['list'],"id"=>$index['id'],"name"=>$index['name']];
		if (array_search($list,$index)){
			$type_h.="<li><a href=\"?list={$index['list']}&listid={$index['id']}\"><font color=\"red\">{$index['name']}</font></a></li>\n";
			$title=$index['name'];
		}else{
			$type_h.="<li><a href=\"?list={$index['list']}&listid={$index['id']}\">{$index['name']}</a></li>\n";
		}
	}
	$type_h.="</ul>\n";
	
	$html.=$api->head($title).$type_h;
	$gopage="?list={$list}&listid={$listid}&";
	$html.=$api->api_page($page_count,$page,$gopage);
	foreach($data as $index){
		$id=base64_encode($index["id"]);
		$pic=$index["img"];
		$title=$index["title"];
		$html.='
			<div class="media">
				<div class="media-left media-middle">
					<img src="'.$pic.'" class="media-object" style="width:120px">
				</div>
				<div class="media-body">
					<a href="view.php?id='.$id.'">
						<h4 class="media-heading">'.$title.'</h4>
					</a>
				</div>
			</div>
			
		';
		$apistr['lists'][]=["title"=>$title,"id"=>$id,"img"=>$pic];
}
$apistr['msg']=['title'=>$title,'list'=>$list,'listid'=>$listid,'page'=>$page,'pagecount'=>$page_count];
$html.=$api->api_page($page_count,$page,$gopage).$api->end();
echo $web_charset?$api->json($apistr):$html;