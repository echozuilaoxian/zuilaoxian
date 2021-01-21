<?php
require '../config.php';
$word=$_GET['word']??NULL;
$list=$_GET['list']??NULL;
$id=$_GET['id']??NULL;
$db_pdo=new mysqlpdo($mysql_config);
function get_type($list){
	global $db_pdo;
	$typelist=$db_pdo->querylists("select distinct type from ggs");
	$html_type.='
		<li class="list-group-item">
		<ul class="breadcrumb">
	';
	$title="鬼故事";
	foreach ($typelist as $row){
		if ($row['type']==$list){
			$title=$row['type'];
			$html_type.='
			<li><a href="?list='.$row['type'].'"><font color="red">'.$row['type'].'</font></a></li>
			';
		}else{
			$html_type.='
			<li><a href="?list='.$row['type'].'">'.$row['type'].'</a></li>
			';
		}
		$str['list'][]=array("list"=>$row['type'],"title"=>$row['type']);

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
	
	$count_sql="SELECT count(*) FROM `ggs`";
	if($word){$count_sql="SELECT count(*) FROM `ggs` where `title` like '%".$word."%'";}
	if($list){$count_sql="SELECT count(*) FROM `ggs` where `type`='".$list."'";}
	if($list & $word){$count_sql="SELECT count(*) FROM `ggs` where `type`='".$list."' and `title` like '%".$word."%'";}
	$count=$db_pdo->counts($count_sql);
	$pagecount=ceil(intval($count)/$pagesize);//总页数
	if ($page>$pagecount){$page=$pagecount;}
	$zhizhen=$pagesize*($page-1);
	$zhizhen=$zhizhen>0?$zhizhen:0;
	$result=$db_pdo->querylists("SELECT * FROM `ggs` LIMIT $zhizhen,$pagesize");
	if($word){$result=$db_pdo->querylists("SELECT * FROM `ggs` where `title` like '%".$word."%' LIMIT $zhizhen,$pagesize");}
	if($list){$result=$db_pdo->querylists("SELECT * FROM `ggs` where `type`='".$list."' LIMIT $zhizhen,$pagesize");}
	if($list & $word){$result=$db_pdo->querylists("SELECT * FROM `ggs` where `type`='".$list."' and `title` like '%".$word."%' LIMIT $zhizhen,$pagesize");}
	$gopage="?";
	if ($word){$gopage="?word=".$word."&";}
	if ($list){$gopage="?list=".$list."&";}
	if ($list && $word){$gopage="?list=".$list."&word=".$word."&";}
	$html.=$api->page_z(0,$count,$pagecount,$pagesize,$page,$gopage);
	$str['msg']=array("name"=>"鬼故事","count"=>$count,"pageall"=>$pagecount,"page"=>$page,"type"=>$list,"list"=>count($result),"keyword"=>$word);
	if ($word){
		$html.= '
			<li class="list-group-item">
			搜索到'.$count.'条记录
			</li>
		';
	}
	foreach($result as $i =>$row){
		$str['lists'][]=array("id"=>$row['id'],"title"=>$row["title"],"list"=>$row["type"]);
		$x=$page*$pagesize+$i+1-$pagesize;
		$html.='
		<li class="list-group-item">
			<a href="?id='.$row['id'].'">'.$row['id'].'.'.$row["title"].'</a>
		</li>';
	}
	$html.=$api->page_z(1,$count,$pagecount,$pagesize,$page,$gopage);

}
if ($id and is_numeric($id)){
	$rs=$db_pdo->queryrow("select * from ggs where id=".$id);
	$rs_p=$db_pdo->queryrow("select * from ggs where id<".$id." order by id desc limit 1");
	$rs_n=$db_pdo->queryrow("select * from ggs where id>".$id." limit 1");
	$title=$rs['title'];
	$content=$rs['content'];
	$content=preg_replace("/www[^>]*com/","",$content);
	$type=$rs['type'];
	
	$str['article']=array("id"=>$id,"title"=>$title,"content"=>$content,"list"=>$type);
	$html=$api->head($title).get_type($type)[1];
	$html.='
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