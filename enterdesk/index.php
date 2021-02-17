<?php
require '../config.php';
$list=$_GET['list']??"";
$id=$_GET['id']??NULL;
$hd=$_GET['hd']??NULL;
use QL\QueryList;
	$type=[
		["list"=>"","name"=>"美女"],
		["list"=>"dalumeinv","name"=>"大陆"],
		["list"=>"rihanmeinv","name"=>"日韩"],
		["list"=>"gangtaimeinv","name"=>"港台"],
		["list"=>"dongmanmeinv","name"=>"动漫"],
		["list"=>"qingchunmeinv","name"=>"清纯"],
		["list"=>"keaimeinv","name"=>"可爱"],
		["list"=>"oumeimeinv","name"=>"欧美"]
	];
	$type_h="<ul class=\"breadcrumb\">\n";
	foreach($type as $row){
		if ($list==$row['list']){
			$title=$row['name'];
			$type_h.="<li><a href=\"?list={$row['list']}\"><font color=\"red\">{$row['name']}</font></a></li>\n";
		}else{
			$type_h.="<li><a href=\"?list={$row['list']}\">{$row['name']}</a></li>\n";
		}
	}
	$type_h.="</ul>\n";
if (!$id){
	$url="https://mm.enterdesk.com/{$list}/{$page}.html";
	$rules=array(
		"title"=>array('img','title'),
		"id"=>array('','href'),
		"img"=>array('img','src')	
	);
	$range='.egeli_pic_m>.egeli_pic_li>dl>dd>a';
	$datahtml = QueryList::get($url,null,[
		'cache' => $huan_path,
		'cache_ttl' => 60*60*12
		])
		->getHtml();

	$data1 = QueryList::html($datahtml)
	->rules($rules)
	->range($range)
	->queryData(
		function($x)use($api){
			$x['id']=$api->cutstr2($x['id'],'bizhi/','.');
			return $x;
		}
	);
	$data2 = QueryList::html($datahtml)->find('.listpages a:last')->href;
	$data2 =str_replace('.html','',$data2);
	$data2 =str_replace('https://mm.enterdesk.com/','',$data2);
	$data2 =str_replace($list.'/','',$data2);
	//
	$html=$api->head($title).$type_h;

	foreach($data1 as $index){
		$apistr['lists'][]=['id'=>$index['id'],'img'=>$index['img'],'title'=>$index['title']];
		$html.='
		<div class="media">
			<div class="media-left">
				<img src="'.$index['img'].'" class="media-object" style="width:130px">
			</div>
			<div class="media-body media-middle">
				<a href="?id='.$index['id'].'">
					<h4 class="media-heading">'.$index['title'].'</h4>
				</a>
			</div>
		</div>
		
	';
	}

	$html.="</div>\r\n<div class=\"container container-small\">";
	$html.=$api->api_page($data2,$page,"?list={$list}&");
	$apistr['msg']=['list'=>$list,'title'=>$title,'page'=>$page,'pagecount'=>$data2];
}


if ($id){
	$url="https://mm.enterdesk.com/bizhi/{$id}.html";
	$rules=array(
		"img"=>array('a','src')	
	);
	$range='.swiper-wrapper>.swiper-slide';
	$datahtml = QueryList::get($url,null,[
		'cache' => $huan_path,
		'cache_ttl' => 60*60*12
		])
		->getHtml();

	$data1 = QueryList::html($datahtml)
	->rules($rules)
	->range($range)
	->queryData(
		function($x)use($hd){
			if ($hd){$x['img']=str_replace('edpic','edpic_source',$x['img']);}
			return $x;
		}
	);

	$data2=QueryList::html($datahtml)->find("title")->text();

	$html.=$api->head($data2)."
	<ul class=\"breadcrumb\">
		<li><a href=\"/\">网站首页</a></li>
		<li><a href=\"./\">漂亮mm</a></li>
		<li>{$data2}</li>
	</ul>
	";
	foreach($data1 as $index){
		$apistr['lists'][]=$index["img"];
		$html.="
			<img src=\"{$index["img"]}\" style=\"width:100%;max-width:400px;\">
			";
	}
	$apistr['msg']=['title'=>$data2];
}
echo $web_charset?$api->json($apistr):$html.$api->end();