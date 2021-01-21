<?php
require '../config.php';
$db = new DBUtils();
$db -> instance('../db/rz.db3');
//
$list=$_GET['list']??NULL;
$word=$_GET['word']??NULL;
$id=$_GET['id']??NULL;


$types=array(
	array("list"=>"","name"=>"全部"),
	array("list"=>"shanggan","name"=>"伤感"),
	array("list"=>"qinggan","name"=>"情感"),
	array("list"=>"kongjian","name"=>"空间"),
	array("list"=>"xinqing","name"=>"心情"),
	array("list"=>"aiqing","name"=>"爱情"),
	array("list"=>"gaoxiao","name"=>"搞笑"),
	array("list"=>"lianlao","name"=>"恋老"),
	array("list"=>"jingdian","name"=>"经典"),
	array("list"=>"juexiang","name"=>"绝想"),
	array("list"=>"ganren","name"=>"感人"),
	array("list"=>"lizhi","name"=>"励志"),
	array("list"=>"gexing","name"=>"个性"),
	array("list"=>"shangxin","name"=>"伤心"),
	array("list"=>"weimei","name"=>"唯美"),
	array("list"=>"shengri","name"=>"生日"),
	array("list"=>"fenshou","name"=>"分手"),
	array("list"=>"feizhuliu","name"=>"非主流"),
	array("list"=>"xingfu","name"=>"幸福"),
	array("list"=>"lvxing","name"=>"旅行"),
	array("list"=>"shilian","name"=>"失恋"),
	array("list"=>"guimi","name"=>"闺蜜"),
	array("list"=>"sinian","name"=>"思念"),
	array("list"=>"beishang","name"=>"悲伤"),
	array("list"=>"yidilian","name"=>"异地恋"),
	array("list"=>"libie","name"=>"离别"),
	array("list"=>"xintong","name"=>"心痛"),
	array("list"=>"biaobai","name"=>"表白"),
	array("list"=>"xinlei","name"=>"心累"),
	array("list"=>"kaixin","name"=>"开心"),
	array("list"=>"duanpian","name"=>"短篇"),
	array("list"=>"biye","name"=>"毕业"),
	array("list"=>"ganen","name"=>"感恩"),
	array("list"=>"qinglv","name"=>"情侣"),
	array("list"=>"youqing","name"=>"友情"),
	array("list"=>"ganwu","name"=>"感悟")
);

function getname($type){
	global $types;
	return $types[array_search($type,array_column($types,"list"))]['name'];
}

function get_type($list){
	global $types;
	$html_type.='
		<li class="list-group-item">
		<ul class="breadcrumb">
	';
	$title="日志大全";
	foreach ($types as $row){
		if ($row['list']==$list){
			$title=$row['name'];
			$html_type.='
			<li><a href="?list='.$row['list'].'"><font color="red">'.$row['name'].'</font></a></li>
			';
		}else{
			$html_type.='
			<li><a href="?list='.$row['list'].'">'.$row['name'].'</a></li>
			';
		}
		$str['list'][]=array("list"=>$row['list'],"title"=>$row['name']);

	}
	$html_type.='
	</ul>
	<form action="?" method="get" class="bs-example bs-example-form" role="form">
			<div class="row">
				<div class="col-lg-6">
					<div class="input-group">
						<input type="text" name="word" class="form-control">
						<input type="hidden" name="list" value="'.$list.'"/>
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit">
								搜!!
							</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</li>
	';
	return [$title,$html_type,$str];
}
if (!$id){
	$html=$api->head(get_type($list)[0]).get_type($list)[1];
	$str=get_type($list)[2];
	$sql="SELECT * FROM content";
	$gopage="?";
	if ($list and $word){
		$sql.=" where type like '%".$list."%' and title like '%".$word."%'";
		$gopage="?list=".$list."&word=".$word."&";
	}elseif ($word){
		$sql.=" where type title like '%".$word."%'";
		$gopage="?word=".$word."&";
	}elseif ($list){
		$sql.=" where type like '%".$list."%'";
		$gopage="?list=".$list."&";
	}
	$count=$db->querySingle(str_replace("*","count(*)",$sql));
	$count=$count>0?$count:0;
	$pagecount=ceil(intval($count)/$pagesize);
	if ($page>$pagecount){$page=$pagecount;}
	$zhizhen=$pagesize*($page-1);
	$zhizhen=$zhizhen>0?$zhizhen:0;
	$result=$db->queryList($sql." LIMIT $zhizhen,$pagesize");
	$str['msg']=array("count"=>$count,"pagecount"=>$pagecount,"pagesize"=>$pagesize,"page"=>$page,"list"=>$list,"keyword"=>$word);
		
	$html.=$api->page_z(0,$count,$pagecount,$pagesize,$page,$gopage);
	if ($word){
	$html.= '
		<li class="list-group-item">
		搜索到'.$count.'条记录
		</li>
	';
	}
	foreach ($result as $i => $row){
		$i=$page*$pagesize-$pagesize+$i+1;
		$title=$row['title'];
		$str['lists'][]=array("id"=>$row['id'],"title"=>$title);
		if ($word){$title=str_replace($word,"<font color=\"red\">".$word."</font>",$title);}
	$html.= '
		<li class="list-group-item">
		'.$i.'.<a href="?id='.$row['id'].'">'.$title.'</a>
		</li>
	';
	}
	$html.=$api->page_z(1,$count,$pagecount,$pagesize,$page,$gopage);
}
if ($id and is_numeric($id)){
	$rs=$db->queryrow("select * from content where id=".$id);
	$rs_p=$db->queryrow("select * from content where id<".$id." order by id desc limit 1");
	$rs_n=$db->queryrow("select * from content where id>".$id." limit 1");
	$title=$rs['title'];
	$content=$rs['content'];
	$type=$rs['type'];
	
	$str['article']=array("id"=>$id,"title"=>$title,"content"=>$content,"type"=>$type);
	$html=$api->head($title);
	$html.='
		<ul class="breadcrumb">
		<li>本文类别：</li>
	';
	foreach(explode("|",$type) as $i => $index){
		$html.="<li><a href=\"?list={$index}\">".getname($index)."</a></li>";
	}
	$html.='
	</ul>
	<h3 class="list-group-item title">'.$title.'</h3>
	<li class="list-group-item content">
	'.$content.'
	</li>
	';
	if ($rs_p){
		$str['prev']=['previd'=>$rs_p['id'],'prevtitle'=>$rs_p['title']];
		$html.='
		<li class="list-group-item prev">
		上一篇：<a href="?id='.$rs_p['id'].'">'.$rs_p['title'].'</a>
		</li>
		';
	}
	if ($rs_n){
		$str['next']=['nextid'=>$rs_n['id'],'nexttitle'=>$rs_p['title']];
		$html.='
		<li class="list-group-item next">
		下一篇：<a href="?id='.$rs_n['id'].'">'.$rs_n['title'].'</a>
		</li>
		';
	}
	
}

echo $web_charset?$api->json($str):$html.$api->end();