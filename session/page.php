<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<?php 
	include_once './session.php';

	$refresh=new refresh($os,$path);

	if($refresh->islogin and $refresh->isvalid) {
?>
	欢迎你，
<?php echo $_COOKIE['User_ID']?>！
	<a href="./logout.php"></a>
<?php 
	} else {
?>
	<form action="./login.php" method="post">
		请登录
		<input type="text" name="id"/>
		<input type="password" name="passwd"/>
		<input type="submit" value="登陆/">
	</form>
<?php 
	}
?>
</body>
</html>
