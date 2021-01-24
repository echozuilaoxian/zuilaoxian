<?php
require '../config.php';
$db = new DBUtils();
$db -> instance('../db/lssdjt.db3');
$month=isset($_GET['month'])?$_GET['month']:date("n");
$day=isset($_GET['day'])?$_GET['day']:date("j");
$time_y=mktime(0,0,0,$month ,$day-1,date('Y'));
$month_y=date('n',$time_y);
$day_y=date('j',$time_y);
$time_t=mktime(0,0,0,$month ,$day+1,date('Y'));
$month_t=date('n',$time_t);
$day_t=date('j',$time_t);


$sql="SELECT * FROM Content where 月={$month} and 日={$day} order by 年 desc";
$result=$db->queryList($sql);
$str['msg']=array("count"=>count($result),"month"=>$month,"day"=>$day);
foreach ($result as $row){
	$img=$api->cutstr($row['内容'],'src=\"','\"');
	if (!$img) $img='noimage.jpeg';
	$str['list'][]=array("id"=>$row['Id'],"title"=>$row['标题'],"year"=>$row['年'],'img'=>$img);
	
}



$html=$api->head("历史上的今天 ".$month."-".$day);
$html.=<<<api

<li class="list-group-item">
	<a href="?month={$month_y}&day={$day_y}">前一天</a>
	 < - {$month}-{$day} - > 
	<a href="?month={$month_t}&day={$day_t}">后一天</a><br>
	输入几月几日，首位不带0：<br>
	<form name="f" action="?" method="get">
	<input type="text" name="month" value="{$month}" size="5" />月
	<input type="text" name="day" value="{$day}" size="4"/>日 
	<input type="submit" value="确定"/>
	</form>
</li>
api;
foreach ($str["list"] as $i => $row){
$html.=<<<api

  <div class="media">
    <div class="media-left">
		<img src="{$row["img"]}" class="media-object" style="width:120px">
    </div>
    <div class="media-body media-middle">
      <a href="v.php?id={$row["id"]}" id="{$row["id"]}" title="{$row["title"]}">
		<h4 class="media-heading">{$row["year"]}年:{$row["title"]}</h4>
	  </a>
	  <p>({$row["title"]})</p>
    </div>
  </div>
  <hr>

api;

}

$html.="</div><div class=\"container container-small\">";
$html.=<<<api
<script>
;!function(){
	$('.media-body a').click(function (event) {
	event.preventDefault();
	layer.msg('加载中，请稍后',{time: 1200,anim:6})
	id=$(this).attr('id');
	$.ajax({
		url:'v.php',
		type:'get',
		data:{
			web_charset:"json",
			id:id
			},
		timeout:'15000',
		async:true,
		dataType:'json',
			success:function(data){
				layer.closeAll();
				if (data.msg){
					$('#myModalLabel').html(data.title)
					$('.modal-body').html(data.content)
					$('#myModal').modal('show')
				}else{
					layer.msg('错误',{time: 1200,anim:6})
				}
			}
		})
	})
	
}();
</script>
api;
echo $web_charset?$api->json($str):$html.$api->end();