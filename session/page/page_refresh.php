<?php 

include_once '../class/class_refresh.php';

?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- <meta http-equiv="refresh" content="1;./page_main.html"> -->

<title>Insert title here</title>
</head>
<body>
<?php 

	$refresh=new refresh($os);
	
	if($refresh->islogin and $refresh->isvalid) {
?>
	会话有效！页面将在1秒后自动跳转，如果您的浏览器没有响应，请点击此<a href="./page_pay.php">链接</a>
	<script type="text/javascript">
	<!-- 
		window.setTimeout("window.location='./page_pay.php'",1000);
	//-->
	</script>
<?php 
	} else {
?>
	会话失效，请重新登陆！页面将在1秒后自动跳转，如果您的浏览器没有响应，请点击此<a href="./page_main.html">链接</a>
	<script type="text/javascript">
	<!-- 
		window.setTimeout("window.location='./page_main.html'",1000);
	//-->
	</script>
<?php 
	}
?>
	<!-- 页面将在1秒后自动跳转，如果您的浏览器没有响应，请点击此<a href="./page_main.html">链接</a> -->

<!-- jump back to page.php
	<script type="text/javascript">
		window.location.href="./page.php";
	</script>
-->
</body>
</html>
