window.onload = function () {
	var a_idx = 0;
	$("body").click(function(e) {
		var a = new Array(
			"富强", "民主", "文明", "和谐",
			"自由", "平等", "公正", "法治",
			"爱国", "敬业", "诚信", "友善"
			);
		var $i = $("<span/>").text(a[a_idx]);
		a_idx = (a_idx + 1) % a.length;
		var x = e.pageX,
		y = e.pageY;
		$i.css({
			"z-index": 144469,
			"top": y - 20,
			"left": x,
			"position": "absolute",
			"font-weight": "bold",
			"color": "#f00"
		});
		$("body").append($i);
		$i.animate({
			"top": y - 180,
			"opacity": 0
		},
		1500,
		function() {
			$i.remove()
		})
	});
};
$(function () {
	setInterval(clock,100);
	
	$("[data-toggle='tooltip']").tooltip();
	//模态框监测关闭后清除内容
	$('#myModal').on('hide.bs.modal', function () {
		$('.modal-title').html('');
		$('.modal-body').html('');
	})
	//qq头像获取
	$("#trash").hide();//清除输入框按钮隐藏
	$("#get_data").click(function(){
		$.ajax({
		url:'key.php',
		type:'post',
		data:{
			send_data:$("#input_data").val()
			},
		timeout:'15000',
		async:true,
		dataType:'json',
			success:function(data){
				if (!data.code){
					$('#myModalLabel').html(data.title)
					$('.modal-body').html(data.html)
				}else{
					$('#myModalLabel').html('无法获取')
					$('.modal-body').html('无法获取')
					//layer.msg('无法获取',{time: 1500,anim:6})
				}
			}
		})
	})
	$("#input_data").bind("input propertychange",function(event){
		if ($("#input_data").val().length>0){
			$("#trash").show();
		}else{
			$("#trash").hide();
		}
	});
	$("#test_data").click(function(){
		$("#input_data").val($(this).text());
		$("#trash").show();
	});
	$("#trash").click(function(){
		$("#input_data").val("");
		if ($("#input_data").val().length<=0){
			$(this).hide();
		}
	});
	$('[data-loading-text]').click(function(){
		$(this).button('loading').delay(1000).queue(function() {
			$(this).button('reset');
			$(this).dequeue();
		});
	});

	gopagehtml=$('.gopage').html();
	var gopage = function(){
		$('.gopage').click(function(){
			turl=location.search;
			if (location.search=="") turl+='?';
			var valuen='';
			s=turl.split('?')[1].split('&');
			for(i=0;i<s.length;i++){
				value=s[i]
				if (value){
					values=value.split('=')
					if (values[0]!='page') valuen+='<input name="'+values[0]+'" value="'+decodeURI(values[1])+'" type="hidden">';
				}
			}
			valuen+='<input name="page" value="" type="text">';
			$(this).html('<a><form action="'+location.pathname+'" method="get">'+valuen+'</form></a>').unbind();
			$('input[name=page]').focus();
			$('input[name=page]').blur(function(){
				$('.gopage').html(gopagehtml);
				gopage();
			});
		})
	}
	gopage();
});
//时钟
function clock(){
  now = new Date();
  year = now.getFullYear();
  month = now.getMonth() + 1;
  day = now.getDate();
  today = ["星期日","星期一","星期二","星期三","星期四","星期五","星期六"];
  week = today[now.getDay()];
  hour = now.getHours();
  min = now.getMinutes();
  sec = now.getSeconds();
  msec = now.getMilliseconds();
  month=month>9?month:"0"+month;
  day=day>9?day:"0"+day;
  hour=hour>9?hour:"0"+hour;
  min=min>9?min:"0"+min;
  sec=sec>9?sec:"0"+sec;
  msec=Math.floor(msec/100)+1;
  msec=msec>9?msec:"0"+msec;
  $("#times").html(year + "-" + month + "-" + day + " " + hour + ":" + min + ":" + sec +":" + msec +" "+ week);
}
