<?php
require '../config.php';
$db = new DBUtils();
$db -> instance('../db/dwz.db3');
$list=$_GET['list']??NULL;
$word=$_GET['word']??NULL;
$id=$_GET['id']??NULL;

function get_type($list){
	global $db;
	$typ1[]=['type'=>0,'name'=>'全部'];
	$rs=$db->queryList("select * from type");
	$rs=array_merge($typ1,$rs);
	$html_type.='
		<li class="list-group-item">
		<ul class="breadcrumb">
	';
	$title="美文";
	foreach ($rs as $row){
		if ($row['type']==$list){
			$title=$row['name'];
			$html_type.='
			<li><a href="?list='.$row['type'].'"><font color="red">'.$row['name'].'</font></a></li>
			';
		}else{
			$html_type.='
			<li><a href="?list='.$row['type'].'">'.$row['name'].'</a></li>
			';
		}
		$str['type'][]=array("list"=>$row['type'],"title"=>$row['name']);

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
		$sql.=" where type=".$list." and title like '%".$word."%'";
		$gopage="?list=".$list."&word=".$word."&";
	}elseif ($word){
		$sql.=" where title like '%".$word."%'";
		$gopage="?word=".$word."&";
	}elseif ($list){
		$sql.=" where type=".$list;
		$gopage="?list=".$list."&";
	}
	$count=$db->querySingle(str_replace("*","count(*)",$sql));
	$count=$count>0?$count:0;
	$pagecount=ceil(intval($count)/$pagesize);
	if ($page>$pagecount){$page=$pagecount;}
	$zhizhen=$pagesize*($page-1);
	$result=$db->queryList($sql." LIMIT $zhizhen,$pagesize");
		$str['msg']=array("count"=>$count,"pagecount"=>$pagecount,"pagesize"=>$pagesize,"page"=>$page,"list"=>$list);
	$html.=$api->page($count,$pagecount,$pagesize,$page,$gopage);
	if ($word){
		$html.='
			<li class="list-group-item">
			搜索到'.$count.'条记录
			</li>
		';
	}
	foreach ($result as $i => $row){
		$str['lists'][]=array("id"=>$row['id'],"title"=>$row['title'],"list"=>$row['type']);
		$i=$page*$pagesize-$pagesize+$i+1;
		$title=$row['title'];
		if ($word){$title=str_replace($word,"<font color=\"red\">".$word."</font>",$title);}
		$html.= '
		<li class="list-group-item">
		'.$i.'.<a href="?id='.$row['id'].'">'.$title.'</a>
		</li>
		';
	}

	$html.=$api->page($count,$pagecount,$pagesize,$page,$gopage);
}
if ($id and is_numeric($id)){
	$rs=$db->queryrow("select * from content where id=".$id);
	$rs_p=$db->queryrow("select * from content where id<".$id." order by id desc limit 1");
	$rs_n=$db->queryrow("select * from content where id>".$id." limit 1");
	$title=$rs['title'];
	$content=$rs['content'];
	$content=preg_replace("/<img[^>]*>/","",$content);
	$list=$rs['type'];
	
	$str['article']=array("id"=>$id,"title"=>$title,"content"=>$content,"list"=>$list);
	$html=$api->head($title).get_type($list)[1];

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