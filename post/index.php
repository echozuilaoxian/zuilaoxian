<?php
require '../config.php';
$post=$_POST['post']??NULL;
$html=$api->head("全国邮编查询").'
<div class="input-group">
	<label for="name">输入邮编或地址进行查询(结果至多显示30条)</label>
</div>
<div class="input-group">
	<span class="input-group-addon"><span class="glyphicon glyphicon-magnet"></span></span>
	<input id="input_data" type="text" class="form-control" placeholder="">
	<span class="input-group-btn" id="btnr"><button id="trash" class="btn btn-default" type="button"><span class="glyphicon glyphicon-trash"></span></button></span>
</div>
<br/>
<button id="get_data1" class="btn btn-primary btn-block" data-loading-text="Loading..."><span class="glyphicon glyphicon-hand-up"></span> Get √
</button>


<script>
	$("#get_data1").click(function(){
		var post=$("#input_data").val()
		if (!post){
			layer.tips("不能为空", "#input_data", {tips: 1});
			return;
		}
		$.ajax({
		url:"?",
		type:"post",
		data:{
			post:post
			},
		timeout:"15000",
		async:true,
		dataType:"json",
			success:function(data){
				if (!data.code){
					$("#myModalLabel").html(data.title)
					$(".modal-body").html(data.html)
					$("#myModal").modal("show")
				}else{
					layer.msg("错误",{time: 1200,anim:6})
				}
			}
		})
	})
</script>
';
if ($post){
	$db = new DBUtils();
	$db -> instance('../db/post.db3');
	$result=$db->queryList("SELECT * FROM [post] where PostNumber like '".$post."' or  Province like '".$post."' or City or  District like '".$post."' or Address like '".$post."' or jd like '".$post."' LIMIT 30");
	if ($result){
		$str=[
		'code'=>0,
		'title'=>'查询结果'.$post
		];
		foreach($result as $r){
			$str['list'][]=$r;
			$str['html'].=$r['PostNumber'].' '.$r['Province'].' '.$r['City'].' '.$r['District'].' '.$r['Address'].' '.$r['jd'].' <hr>';
		}
	}else{
		$str=['code'=>1];
	}
}
echo $post?$api->json($str):$html.$api->end();