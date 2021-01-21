<?php
require_once("../config.php");
$db = new DBUtils();
$db -> instance('../db/baike.db3');
$word=$_GET['word']??NULL;

$count_sql="SELECT count(*) FROM [content]";
if($word){$count_sql="SELECT count(*) FROM [content] where tmnr like '%".$word."%'";}
//
$count=$db->querySingle($count_sql);
$pagecount=ceil(intval($count)/$pagesize);//总页数
if ($page>$pagecount){$page=$pagecount;}
$zhizhen=$pagesize*($page-1);
$result=$db->queryList("SELECT * FROM [content] LIMIT $zhizhen,$pagesize");
if($word){$result=$db->queryList("SELECT * FROM [content] where tmnr like '%".$word."%' LIMIT $zhizhen,$pagesize");}
$api_array=array();
$api_array['msg']=array("name"=>"百科问答","count"=>(int)$count,"pageall"=>$pagecount,"page"=>$page,"list"=>count($result),"keyword"=>$word);
	foreach($result as $i =>$row){
		$api_array['list'][]=["id"=>$row['tmxh'],"title"=>$row["tmnr"],"tmlx"=>$row["tmlx"],"kind"=>$row['kind'],"tmda1"=>$row['tmda1'],"tmda2"=>$row['tmda2'],"tmda3"=>$row['tmda3'],"tmda4"=>$row['tmda4'],"tmda5"=>$row['tmda5'],"tmda6"=>$row['tmda6'],"tmda"=>$row['tmda']];
	}
	
$html=$api->head("百科问答");
$html.=<<<api
<style>
	#daan{
		border: solid 1px #ccc;
		padding: 3px 5px;
		margin: 1px;
		border-radius: 3px;
		display:inline-block;
	}
</style>
<li class="list-group-item">
	<form method="get" action="?" class="bs-example bs-example-form" role="form">
		<div class="row">
			<div class="col-lg-6">
				<div class="input-group">
					<input type="text" name="word" id="keyword" class="form-control">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit">
							确定
						</button>
					</span>
				</div>
			</div>
		</div>
	</form>
json页面:<a href="{$thisurl}web_charset=json{$query_string}">{$thisurl}web_charset=json{$query_string}</a>
</li>
api;
$gopage=$word?"?word=".$word."&":"?";
$html.=$api->page_z(0,$count,$pagecount,$pagesize,$page,$gopage);

for($i=0;$i<$api_array['msg']['list'];$i++){
	$tmda='';
	if ($api_array['list'][$i]['tmda1']) $tmda.='<span id="daan">1.'.$api_array['list'][$i]['tmda1'].'</span>';
	if ($api_array['list'][$i]['tmda2']) $tmda.='<span id="daan">2.'.$api_array['list'][$i]['tmda2'].'</span>';
	if ($api_array['list'][$i]['tmda3']) $tmda.='<span id="daan">3.'.$api_array['list'][$i]['tmda3'].'</span>';
	if ($api_array['list'][$i]['tmda4']) $tmda.='<span id="daan">4.'.$api_array['list'][$i]['tmda4'].'</span>';
	if ($api_array['list'][$i]['tmda5']) $tmda.='<span id="daan">5.'.$api_array['list'][$i]['tmda5'].'</span>';
	if ($api_array['list'][$i]['tmda6']) $tmda.='<span id="daan">6.'.$api_array['list'][$i]['tmda6'].'</span>';
	
	$daan=str_split($api_array['list'][$i]['tmda']);
	$daant='';
	foreach($daan as $o => $rowda){
		if ($rowda) $daant.=($o+1);
	}
	$daan=implode(",", str_split($daant));
$html.=<<<api

<li class="list-group-item">
	<h4>{$api_array['list'][$i]['id']}.{$api_array['list'][$i]['title']}</h4>
	<p id="{$api_array['list'][$i]['id']}">{$tmda}</p>
	题目类型：{$api_array['list'][$i]['kind']} <button id="{$api_array['list'][$i]['id']}" class="btn btn-default">答案</button>
	<span style="display:none">
		<font color="red">{$daan}</font>
	</span>
</li>

api;
}
$html.=$api->page_z(1,$count,$pagecount,$pagesize,$page,$gopage);
$html.=<<<api

<script>
;!function(){
	$('button').click(function (){
		$(this).next().fadeIn();
		var daan=$(this).next().text().trim().split(',');
		var id=$(this).prev().attr('id');
		console.log(id);
		for (i=0;i<daan.length;i++){
		//console.log(daan[i]);
			$('#'+id+'>#daan').eq(daan[i]-1).css("border",'solid 1px #ff0000')
		}
	})

	
	
}();
</script>

api;
echo $web_charset?$api->json($api_array):$html.$api->end();