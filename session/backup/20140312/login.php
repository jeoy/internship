<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- <meta http-equiv="refresh" content="1;./page.php"> -->
<meta http-equiv="refresh" content="1;./page.php">
<title>Insert title here</title>
</head>
<body>
<?php 
	include_once './session.php';

	$login=new login($os,$_POST['id'],$_POST['passwd']);
	
	if($login->issucceed) {
?>
	登陆成功！
<?php 
	} else {
?>
	登录失败！
<?php 
	}
?>
	页面将在1秒后自动跳转，如果您的浏览器没有响应，请点击此<a href="./page.php">链接</a>

<!-- jump back to page.php
	<script type="text/javascript">
		window.location.href="./page.php";
	</script>
-->
</body>
</html>
