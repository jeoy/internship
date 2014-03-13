<?php 

include_once '../class/class_logout.php';

?>

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

	$logout=new logout($os);
?>
	页面将在1秒内自动跳转，如果您的浏览器没有响应，请点击此<a href="./page.php">链接</a>
</body>
</html>
