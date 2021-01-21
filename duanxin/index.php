<?php
require '../config.php';
$id=$_GET['id']??NULL;
$type=$_GET['type']??NULL;
$word=$_GET['word']??NULL;
//
$db = new DBUtils();
$db -> instance('../db/duanxin.db3');
//
$html=$api->head("短信大全");
$html.=<<<api

<li class="list-group-item">
json页面:<a href="{$thisurl}web_charset=json{$query_string}">{$thisurl}web_charset=json{$query_string}</a>
<hr/>
		<form method="get" action="?" class="bs-example bs-example-form" role="form">
			<div class="row">
				<div class="col-lg-6">
					<div class="input-group">
						<input name="action" type="hidden" value="list">
						<input name="type" type="hidden" value="{$type}">
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

<hr/>
<div class="panel-group" id="accordion">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a data-toggle="collapse" data-parent="#accordion" 
				href="#collapseOne">
				【祝福无限】
			</a>
			<a data-toggle="collapse" data-parent="#accordion" 
				href="#collapseTwo">
				【节日语录】
			</a>
			<a data-toggle="collapse" data-parent="#accordion" 
				href="#collapseThree">
				【经典语录】
			</a>
			<a data-toggle="collapse" data-parent="#accordion" 
				href="#collapseFour">
				【爱情密语】
			</a>
		</div>
		<div id="collapseOne" class="panel-collapse collapse collapse" in>
	<ul class="breadcrumb list-group-item">
	<li><a href="?type=36">问候</a></li>
	<li><a href="?type=37">思念</a></li>
	<li><a href="?type=38">感谢</a></li>
	<li><a href="?type=39">祝福</a></li>
	<li><a href="?type=10">生日</a></li>
	<li><a href="?type=11">纪念</a></li>
	</ul>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse">
	<ul class="breadcrumb list-group-item">
	<li><a href="?type=12">元旦</a></li>
	<li><a href="?type=13">春节</a></li>
	<li><a href="?type=14">元宵节</a></li>
	<li><a href="?type=15">情人节</a></li>
	<li><a href="?type=16">妇女节</a></li>
	<li><a href="?type=17">愚人节</a></li>
	<li><a href="?type=18">植树节</a></li>
	<li><a href="?type=19">清明节</a></li>
	<li><a href="?type=20">劳动节</a></li>
	<li><a href="?type=21">青年节</a></li>
	<li><a href="?type=22">母亲节</a></li>
	<li><a href="?type=23">儿童节</a></li>
	<li><a href="?type=24">父亲节</a></li>
	<li><a href="?type=25">端午节</a></li>
	<li><a href="?type=26">建党节</a></li>
	<li><a href="?type=27">建军节</a></li>
	<li><a href="?type=28">七夕节</a></li>
	<li><a href="?type=29">教师节</a></li>
	<li><a href="?type=30">中秋节</a></li>
	<li><a href="?type=31">国庆节</a></li>
	<li><a href="?type=32">重阳节</a></li>
	<li><a href="?type=33">万圣节</a></li>
	<li><a href="?type=34">感恩节</a></li>
	<li><a href="?type=35">圣诞节</a></li>
	</ul>
		</div>
		<div id="collapseThree" class="panel-collapse collapse">
	<ul class="breadcrumb list-group-item">
	<li><a href="?type=40">道歉</a></li>
	<li><a href="?type=41">励志格言</a></li>
	<li><a href="?type=48">经典台词</a></li>
	<li><a href="?type=9">调侃能手</a></li>
	<li><a href="?type=49">大话星仔</a></li>
	<li><a href="?type=3">短信幽默</a></li>
	<li><a href="?type=7">整人专家</a></li>
	<li><a href="?type=8">谷话俚语</a></li>
	</ul>
		</div>
		<div id="collapseFour" class="panel-collapse collapse">
	<ul class="breadcrumb list-group-item">
	<li><a href="?type=42">求爱</a></li>
	<li><a href="?type=43">热恋</a></li>
	<li><a href="?type=44">网恋</a></li>
	<li><a href="?type=45">求婚</a></li>
	<li><a href="?type=46">分手</a></li>
	<li><a href="?type=47">爱情密码</a></li>
	</ul>
		</div>
	</div>
</div>
</li>
api;


	$sql="SELECT * FROM content";
	$gopage="?";
if ($type && !$word){
	$sql=$sql." where type=".$type;
	$gopage="?type=".$type."&";
}elseif (!$type && $word){
	$sql=$sql." where content like '%".$word."%'";
	$gopage="?word=".$word."&";
}elseif ($type && $word){
	$sql=$sql." where type=".$type." and content like '%".$word."%'";
	$gopage="?type=".$type."&word=".$word."&";
}

//总量
$count=$db->querySingle(str_replace("*","count(*)",$sql));

$pagecount=ceil(intval($count)/$pagesize);//总页数
if ($page>$pagecount){$page=$pagecount;}
$zhizhen=$pagesize*($page-1);
$result=$db->queryList($sql." LIMIT ".$zhizhen.",".$pagesize);
//分页
$html.=$api->page_z(0,$count,$pagecount,$pagesize,$page,$gopage);

$api_array['msg']=array("name"=>"短信大全","count"=>$count,"pageall"=>$pagecount,"page"=>$page,"type"=>$type,"list"=>count($result),"keyword"=>$word);
//循环集合
foreach($result as $i => $row){
	$api_array['list'][]=array("content"=>$row["content"],"type"=>$row["type"]);
	$x=$page*$pagesize+$i+1-$pagesize;
if ($mobile){$content="<a href=\"sms:?body=".$row["content"]."\">".$row["content"]."</a>";}else{$content=$row["content"];}
$html.=<<<api

<li class="list-group-item">
{$x}.{$content}
</li>
api;
}


//分页
$html.=$api->page_z(1,$count,$pagecount,$pagesize,$page,$gopage);
echo  $web_charset?$api->json($api_array):$html.$api->end();