<?php 
	include_once '../class/class_pay.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<?php 
	$cps_id=@$_COOKIE['CPS_ID'];
	if($cps_id!=""&&$cps_id!=null) {
		$pay = new pay($cps_id);
		echo "用户从".$pay->cps_id."访问本站，共有".$pay->cps_number."次访问来自该网站";
	}
?>
返回<a href="./page_main.html">首页</a>
</body>
</html>