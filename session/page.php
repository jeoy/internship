<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Insert title here</title>
</head>
<body>
<?php
	include_once './session.php';
	
	$refresh=new refresh($os,$path);

	if($refresh->islogin and $refresh->isvalid) {
?>
	»¶Ó­Äã£¬<?php echo $_COOKIE['User_ID']?>£¡
	<a href="./logout.php">ÍË³öµÇÂ¼</a>
<?php
	}
	else {
?>
	<form action="./login.php" method="post">
		ÇëµÇÂ¼
		<input type="text" name="id"/>
		<input type="password" name="passwd"/>
		<input type="submit" value="µÇÂ½">
	</form>
<?php 
	}
?>
</body>
</html>
