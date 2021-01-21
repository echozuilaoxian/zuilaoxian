<?php
require_once("../config.php");
$id=isset($_GET['id'])?$_GET['id']:NULL;
use \Curl\Curl;
$curl = new Curl();
if ($id and is_numeric($id)){
	$db_pdo=new mysqlpdo($mysql_config);
	$result=$db_pdo->queryrow("SELECT * FROM haha where ids=".$id);
	//print_r($result);
	if ($result["url"]){
		$str=array(
			"msg"=>true,
			"id"=>$id,
			"title"=>$result['title'],
			"img"=>$result['img'],
			"video"=>$result['url']
		);
	}else{
		$huan=$huan_path."/haha_video_".$id;
		if (file_exists($huan) and $api->filetimes($huan,"d")<360 and !$retxt){
			$api_str=file_get_contents($huan);
		}else{
		$url="http://haha.sogou.com/port/getNextJoke?jid=".$id;
		$api_str=$curl->get($url);
			if ($curl->error){
				@unlink($huan);
				$str=array("msg"=>false);
			}else{
				file_put_contents($huan,$api_str);
				$json_str=json_decode($api_str);
				$ids=$json_str->list[0]->id;
				$title=$json_str->list[0]->title;
				$img=$json_str->list[0]->image_url;
				$url=$json_str->list[0]->text;
				$str=array(
					"msg"=>true,
					"id"=>$ids,
					"title"=>$title,
					"img"=>$img,
					"video"=>$url
				);
				foreach($json_str->list as $i => $row){
					$ids=$row->id;
					$type=$row->type;
					$title=$row->title;
					$img=$row->image_url;
					$url=$row->text;
					$result_=$db_pdo->queryrow("SELECT * FROM haha where ids=".$ids);
					if ($type==3){
						if (!$result_){$db_pdo->execs("insert into haha (ids,title,img,url,date)VALUES(".$ids.",'".$title."','".$img."','".$url."','".$now."')");}
						if (!$result_["url"]){$db_pdo->execs("update haha set url='".$url."',date='".$now."' where ids=".$ids);}
					}
				}
			}
		}
	}
}else{
	$str=array("msg"=>false);
}
$style="width:100%;height:400px;max-height:440px;";
if ($str["msg"]){
$html.=<<<api
		<center>
		{$str["title"]}<br/>
		<div id="video" style="{$style}"></div>
		<a href="{$str["video"]}">视频链接</a> <a href="v.php?id={$str["id"]}" target="_blank">视频播放链接</a><br/>
		json页面:<a href="{$thisurl}web_charset=json{$query_string}">{$thisurl}web_charset=json{$query_string}</a>
		</center>
		<script type="text/javascript" src="../class/ckplayer/ckplayer.js"></script>
		<script type="text/javascript">
			var videoObject = {
				container: '#video', //容器的ID或className
				variable: 'player',//播放函数名称
				//flashplayer:true, //强制flash播放器
				autoplay: true, //自动播放
				loop:false,
				mobileCkControls:false,//是否在移动端（包括ios）环境中显示控制栏
				mobileAutoFull:false,//
				poster:'{$str["img"]}',//封面图片
				video: [//视频地址列表形式
					['{$str["video"]}', 'video/mp4', '中文标清', 0],
				]
			};
			var player = new ckplayer(videoObject);
			$(function () {
				$('#myModal').on('hide.bs.modal', function () {
					player.playOrPause();
					$('#video').html('');
					$('div[class*=menuch]').remove();
				})
			});
		</script>
api;
}else{
$html.=$api->head("获取视频出错");
$html.="获取视频出错";
}
echo $web_charset?$api->json($str):$html;