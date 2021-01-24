<?php
require_once("../config.php");
$db = new DBUtils();
$db -> instance('../db/baike.db3');
$word=$_GET['word']??NULL;
$list=$_GET['list']??NULL;
function get_type($list){
	global $db;
	$typ1[]=['id'=>0,'kind'=>'全部'];
	$rs=$db->queryList("select * from type");
	$rs=array_merge($typ1,$rs);
	$html_type.='
		<li class="list-group-item">
		<ul class="breadcrumb">
	';
	$title="百科问答";
	foreach ($rs as $row){
		if ($row['id']==$list){
			$title=$row['kind'];
			$html_type.='
			<li><a href="?list='.$row['id'].'"><font color="red">'.$row['kind'].'</font></a></li>
			';
		}else{
			$html_type.='
			<li><a href="?list='.$row['id'].'">'.$row['kind'].'</a></li>
			';
		}
		$str['type'][]=array("list"=>$row['id'],"title"=>$row['kind']);

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

$html=$api->head(get_type($list)[0]).get_type($list)[1];
$str=get_type($list)[2];
$html.=<<<api
<style>
	#daan{
		border: solid 1px #eee;
		padding: 3px 5px;
		margin: 1px;
		border-radius: 3px;
		display:inline-block;
	}
</style>
api;


$count_sql="SELECT count(*) FROM [content]";
$gopage="?";
if($word and !$list){
	$count_sql.=" where tmnr like '%".$word."%'";
	$gopage='?word='.$word.'&';
}
if($list and !$word){
	$count_sql.=" where kind=".$list;
	$gopage='?list='.$list.'&';
}
if($word and $list){
	$count_sql.=" where tmnr like '%".$word."%' and kind=".$list;
	$gopage='?word='.$word.'&list='.$list.'&';
}

//
$count=$db->querySingle($count_sql);
$pagecount=ceil(intval($count)/$pagesize);//总页数
if ($page>$pagecount){$page=$pagecount;}
$zhizhen=$pagesize*($page-1);
$result=$db->queryList(str_replace('count(*)','*',$count_sql)." LIMIT $zhizhen,$pagesize");
$apistr['msg']=array("name"=>$title,"count"=>$count,"pageall"=>$pagecount,"page"=>$page,"keyword"=>$word);


$html.=$api->page($count,$pagecount,$pagesize,$page,$gopage);
foreach($result as $row){
	$apistr['list'][]=["id"=>$row['tmxh'],"title"=>$row["tmnr"],"tmlx"=>$row["tmlx"],"kind"=>$row['kind'],"tmda1"=>$row['tmda1'],"tmda2"=>$row['tmda2'],"tmda3"=>$row['tmda3'],"tmda4"=>$row['tmda4'],"tmda5"=>$row['tmda5'],"tmda6"=>$row['tmda6'],"tmda"=>$row['tmda']];
	$tmda='';
	if ($row['tmda1']) $tmda.='<span id="daan">1.'.$row['tmda1'].'</span>';
	if ($row['tmda2']) $tmda.='<span id="daan">2.'.$row['tmda2'].'</span>';
	if ($row['tmda3']) $tmda.='<span id="daan">3.'.$row['tmda3'].'</span>';
	if ($row['tmda4']) $tmda.='<span id="daan">4.'.$row['tmda4'].'</span>';
	if ($row['tmda5']) $tmda.='<span id="daan">5.'.$row['tmda5'].'</span>';
	if ($row['tmda6']) $tmda.='<span id="daan">6.'.$row['tmda6'].'</span>';
	
	$daan=str_split($row['tmda']);
	$daant='';
	foreach($daan as $o => $rowda){
		if ($rowda) $daant.=($o+1);
	}
	$daan=implode(",", str_split($daant));
	$html.='
	<li class="list-group-item">
		<h4>'.$row['tmxh'].'.'.$row['tmnr'].'</h4>
		<p id="'.$row['tmxh'].'">'.$tmda.'</p>
		题目类型：'.get_type($row['kind'])[0].' <button id="'.$row['tmxh'].'" class="btn btn-default">答案</button>
		<span style="display:none">
			<font color="red">'.$daan.'</font>
		</span>
	</li>

	';
}
$html.=$api->page($count,$pagecount,$pagesize,$page,$gopage);


$html.='
	<script>
	;!function(){
		$("button").click(function (){
			$(this).next().fadeIn();
			var daan=$(this).next().text().trim().split(",");
			var id=$(this).prev().attr("id");
			console.log(id);
			for (i=0;i<daan.length;i++){
			//console.log(daan[i]);
				$("#"+id+">#daan").eq(daan[i]-1).css("border","solid 1px #ff0000")
			}
		})

		
		
	}();
	</script>
	';
echo $web_charset?$api->json($apistr):$html.$api->end();