<?php
require_once("../config.php");
$word=isset($_GET['word'])?$_GET['word']:NULL;
$type=isset($_GET['type'])?$_GET['type']:NULL;
$html=$api->head("谜语大全2");
$html.=<<<api
<li class="list-group-item">
		<form method="get" action="?" class="bs-example bs-example-form" role="form">			<div class="row">				<div class="col-lg-6">					<div class="input-group">						<input type="text" name="word" id="keyword" class="form-control">						<span class="input-group-btn">							<button class="btn btn-default" type="submit">								确定							</button>						</span>					</div>				</div>			</div>		</form>
json页面:<a href="{$thisurl}web_charset=json{$query_string}">{$thisurl}web_charset=json{$query_string}</a>

<script>
function display(obj){
if (obj.style.display=='none')
	obj.style.display='';
else
    obj.style.display='none';
}
</script>
api;

$db_pdo=new mysqlpdo($mysql_config);
$typelist=$db_pdo->querylists("select distinct type from miyu2");

$html.=<<<api
<div class="dropdown">
	<button type="button" class="btn dropdown-toggle" id="dropdownMenu1" 
			data-toggle="dropdown">
		所有分类
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">

api;
foreach ($typelist as $i => $row){
$html.=<<<api
			<li><a role="menuitem" tabindex="-1" href='?type={$row['type']}'>{$row['type']}</a></li>\r\n
api;
}
$html.=<<<api

	</ul>
</div>
</li>
\r\n
api;


$count_sql="SELECT count(*) FROM miyu2";
if($word){$count_sql="SELECT count(*) FROM miyu2 where title like '%".$word."%'";}
if($type){$count_sql="SELECT count(*) FROM miyu2 where type='".$type."'";}
//
$count=$db_pdo->counts($count_sql);
$pagecount=ceil(intval($count)/$pagesize);//总页数
if ($page>$pagecount){$page=$pagecount;}
$zhizhen=$pagesize*($page-1);
$result=$db_pdo->querylists("SELECT * FROM miyu2 LIMIT $zhizhen,$pagesize");
if($word){$result=$db_pdo->querylists("SELECT * FROM miyu2 where title like '%".$word."%' LIMIT $zhizhen,$pagesize");}
if($type){$result=$db_pdo->querylists("SELECT * FROM miyu2 where type='".$type."' LIMIT $zhizhen,$pagesize");}
$gopage="?";
if ($word){
	$gopage="?word=".$word."&";
}
if ($type){
	$gopage="?type=".$type."&";
}
$html.=$api->page_z(0,$count,$pagecount,$pagesize,$page,$gopage);

$api_array['msg']=array("name"=>"谜语大全2","count"=>$count,"pageall"=>$pagecount,"page"=>$page,"type"=>$type,"list"=>count($result),"keyword"=>$word);
	foreach($result as $i =>$row){
		$api_array['list'][]=array("id"=>$row['id'],"title"=>$row["title"],"type"=>$row["type"],"content"=>$row['content']);
	$x=$page*$pagesize+$i+1-$pagesize;
$html.=<<<api

<li class="list-group-item">
	<big>{$x}.{$row["title"]}</big><small>({$row["type"]})</small><br/>
		<a id="a" class="btn btn-default">答案</a>
			<span style="display:none">
				<font color="red">{$row["content"]}</font>
			</span>
</li>

api;
	}
$html.=$api->page_z(1,$count,$pagecount,$pagesize,$page,$gopage);

$html.=<<<api

<script>
;!function(){
	$('a#a').click(function (){
		console.log($('a#a').index(this))
		//下一个兄弟元素
		$(this).next().toggle()
		//指定元素
		//$('span#str').eq($('a#a').index(this)).toggle()
	})
}();
</script>

api;
$html.=$api->end();
$html=$web_charset?$api->json($api_array):$html;
echo $html;