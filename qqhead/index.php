<?php
require_once("../config.php");
echo $api->head("qq头像昵称获取");
?>
<div class="input-group">
	<label for="name">输入QQ号码，如：<a id="test_data">123456789</a></label>
</div>
<br/>
<div class="input-group">
	<span class="input-group-addon"><span class="glyphicon glyphicon-magnet"></span></span>
	<input id="input_data" type="text" class="form-control" placeholder="">
	<span class="input-group-btn" id="btnr"><button id="trash" class="btn btn-default" type="button"><span class="glyphicon glyphicon-trash"></span></button></span>
</div>
<br/>
<button id="get_data" class="btn btn-primary btn-block" data-loading-text="Loading..." data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-hand-up"></span> Get √
</button>

<?php
echo $api->end();