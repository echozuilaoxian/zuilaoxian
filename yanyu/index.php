<?php
require '../config.php';
$db = new DBUtils();
$db -> instance('../db/yanyu.db3');
$word=$_GET['word']??NULL;

$count_sql="SELECT count(*) FROM [content]";
$gopage='?';
if($word){
	$count_sql.=" where title like '%".$word."%'";
	$gopage='?word='.$word.'&';
}
//
$count=$db->querySingle($count_sql);
$pagecount=ceil(intval($count)/$pagesize);//总页数
if ($page>$pagecount){$page=$pagecount;}
$zhizhen=$pagesize*($page-1);
$result=$db->queryList(str_replace('count(*)','*',$count_sql)." LIMIT $zhizhen,$pagesize");

$api_array['msg']=array("name"=>"谚语","count"=>$count,"pageall"=>$pagecount,"page"=>$page,"keyword"=>$word);
foreach($result as $row){
	$api_array['list'][]=array("id"=>$row['id'],"title"=>$row["title"],"content"=>$row['content']);
}
	
$html=$api->head("谚语");
$html.=<<<api
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
	</li>
api;
$html.=$api->page($count,$pagecount,$pagesize,$page,$gopage);
foreach($api_array['list'] as $row){
$html.=<<<api

<li class="list-group-item">
	<h4>{$row['id']}.{$row['title']}</h4>
</li>

api;
}
$html.=$api->page($count,$pagecount,$pagesize,$page,$gopage);

echo $web_charset?$api->json($api_array):$html.$api->end();