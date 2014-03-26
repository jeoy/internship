<?php 

include_once '../class/class_session.php';

//服务端返回JSON数据
class generateSessionJSONP extends session {
	function generateSessionJSONP($os) {
		$control=2;
		$this->session($os,$control,"","");
	}
}
$generateSessionJSONP=new generateSessionJSONP($os);
$result=$generateSessionJSONP->json_cookie;

//动态执行回调函数
$callback=$_GET['callback'];
echo $callback."($result)";
//echo $result;
exit;
?>