<?php
require '../config.php';

$db_pdo=new mysqlpdo($mysql_config);
$types=array(
	array('list'=>'','title'=>'全部'),
	array('list'=>'lizhiwenzhang','title'=>'励志文章'),
	array('list'=>'lizhigushi','title'=>'励志故事'),
	array('list'=>'lizhimingyan','title'=>'励志名言'),
	array('list'=>'lizhidianying','title'=>'励志电影'),
	array('list'=>'renshengganwu','title'=>'人生感悟'),
	array('list'=>'jingdianyulu','title'=>'经典语录'),
	array('list'=>'zhichanglizhi','title'=>'职场励志'),
	array('list'=>'qingchunlizhi','title'=>'青春励志'),
	array('list'=>'weirenchushi','title'=>'为人处世'),
	array('list'=>'lizhiyanjiang','title'=>'励志演讲'),
	array('list'=>'meiwen','title'=>'经典美文'),
	array('list'=>'lizhikouhao','title'=>'励志口号'),
	array('list'=>'lizhijiaoyu','title'=>'励志教育'),
	array('list'=>'daxueshenglizhi','title'=>'大学生励志'),
	array('list'=>'chenggonglizhi','title'=>'成功励志'),
	array('list'=>'lizhirenwu','title'=>'励志人物'),
	array('list'=>'mingrenmingyan','title'=>'名人名言'),
	array('list'=>'lizhigequ','title'=>'励志歌曲'),
	array('list'=>'zheli','title'=>'人生哲理'),
	array('list'=>'jingdianyuduan','title'=>'经典句子'),
	array('list'=>'lizhichuangye','title'=>'励志创业'),
	array('list'=>'gaosanlizhi','title'=>'高三励志'),
	array('list'=>'jiatingjiaoyu','title'=>'家庭教育'),
	array('list'=>'ganenlizhi','title'=>'感悟亲情'),
	array('list'=>'shanggan','title'=>'伤感日志'),
	array('list'=>'lizhishuji','title'=>'励志书籍'),
	array('list'=>'lizhishige','title'=>'励志诗歌')
);

function get_type($list){
	global $types;
	$html_type.='
		<li class="list-group-item">
		<ul class="breadcrumb">
	';
	$title="励志文章";
	foreach ($types as $row){
		if ($row['list']==$list){
			$title=$row['title'];
			$html_type.='
			<li><a href="?list='.$row['list'].'"><font color="red">'.$row['title'].'</font></a></li>
			';
		}else{
			$html_type.='
			<li><a href="?list='.$row['list'].'">'.$row['title'].'</a></li>
			';
		}
		$str['list'][]=array("list"=>$row['list'],"title"=>$row['title']);

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

$list=$_GET['list']??'';
$word=$_GET['word']??NULL;
$id=$_GET['id']??NULL;

if (!$id){
	$html=$api->head(get_type($list)[0]).get_type($list)[1];
	$str=get_type($list)[2];

	$sql="SELECT * FROM lizhi";
	$gopage="?";
	if ($list and $word){
		$sql.=' where type2=\''.$list.'\' and title like \'%'.$word.'%\'';
		$gopage='?list='.$list.'&word='.$word.'&';
	}elseif ($word){
		$sql.=' where title like \'%'.$word.'%\'';
		$gopage='?word='.$word.'&';
	}elseif ($list){
		$sql.=' where type2=\''.$list.'\'';
		$gopage='?list='.$list.'&';
	}
	$count=$db_pdo->counts(str_replace('*','count(*)',$sql));
	$count=$count>0?$count:0;

	$pagecount=ceil(intval($count)/$pagesize);
	if ($page>$pagecount){$page=$pagecount;}
	$zhizhen=$pagesize*($page-1);
	$result=$db_pdo->querylists($sql." LIMIT $zhizhen,$pagesize");
		$str['msg']=array("count"=>$count,"pagecount"=>$pagecount,"pagesize"=>$pagesize,"page"=>$page,"list"=>$list);
	$html.=$api->page($count,$pagecount,$pagesize,$page,$gopage);
	if ($word){
		$html.= '
			<li class="list-group-item">
			搜索到'.$count.'条记录
			</li>
		';
	}
	foreach ($result as $i => $row){
		$str['lists'][]=array("id"=>$row['id'],"title"=>$row['title'],"list"=>$row['type2']);
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
	$rs=$db_pdo->queryrow("select * from lizhi where id=".$id);
	$rs_p=$db_pdo->queryrow("select * from lizhi where id<".$id." order by id desc limit 1");
	$rs_n=$db_pdo->queryrow("select * from lizhi where id>".$id." limit 1");
	$title=$rs['title'];
	$content=$rs['content'];
	$type=$rs['type2'];
	
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