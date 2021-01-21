<?php
require '../config.php';
$db = new DBUtils();
$db -> instance('../db/mjpf.db3');
$word=$_GET['word']??NULL;

$count_sql="SELECT count(*) FROM [content]";
if($word){$count_sql="SELECT count(*) FROM [content] where title like '%".$word."%'";}
//
$count=$db->querySingle($count_sql);
$pagecount=ceil(intval($count)/$pagesize);//总页数
if ($page>$pagecount){$page=$pagecount;}
$zhizhen=$pagesize*($page-1);
$result=$db->queryList("SELECT * FROM [content] LIMIT $zhizhen,$pagesize");
if($word){$result=$db->queryList("SELECT * FROM [content] where title like '%".$word."%' LIMIT $zhizhen,$pagesize");}
$api_array=array();
$api_array['msg']=array("name"=>"民间偏方","count"=>(int)$count,"pageall"=>$pagecount,"page"=>$page,"list"=>count($result),"keyword"=>$word);
	foreach($result as $i =>$row){
		$api_array['list'][]=array("id"=>$row['id'],"title"=>$row["title"],"content"=>$row["content"]);
	}
	
$html=$api->head("民间偏方");
$html.=<<<api
<li class="list-group-item">
		<form method="get" action="?" class="bs-example bs-example-form" role="form">			<div class="row">				<div class="col-lg-6">					<div class="input-group">						<input type="text" name="word" id="keyword" class="form-control">						<span class="input-group-btn">							<button class="btn btn-default" type="submit">								确定							</button>						</span>					</div>				</div>			</div>		</form>
json页面:<a href="{$thisurl}web_charset=json{$query_string}">{$thisurl}web_charset=json{$query_string}</a>
</li>
api;
//print_r($api_array);
if ($word){
$gopage="?word=".$word."&";
}else{
$gopage="?";
}
$html.=$api->page_z(0,$count,$pagecount,$pagesize,$page,$gopage);

for($i=0;$i<$api_array['msg']['list'];$i++){
$html.=<<<api

<li class="list-group-item">
	<h4>{$api_array['list'][$i]['id']}.{$api_array['list'][$i]['title']}</h4>
	<p>典故：{$api_array['list'][$i]['content']}</p>
</li>

api;
}
$html.=$api->page_z(1,$count,$pagecount,$pagesize,$page,$gopage);

$html.=$api->end();
echo $web_charset?$api->json($api_array):$html;