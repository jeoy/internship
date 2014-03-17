<?php 

$cookie=array(
		'Session_ID'=>"1234",
		'Token_Date'=>"1234",
		'User_ID'=>"1234",
		'Token_ID'=>"1234");

set_cookie_immediate("", $cookie);

//set_cookie_immediate("test", "test_value");

if($_GET['checkCookie']=='Fail') {
	
} else {
	
}

function set_cookie_immediate($var, $value, $time=0, $domain='', $s='') {
	$_COOKIE[$var] = $value;
	if(is_array($value)){
		foreach($value as $k=>$v){
			//setcookie($var.'['.$k.']', $v, $time, $domain, $s);
			setcookie($k, $v, $time, $domain, $s);
		}
	}else{
		setcookie($var, $value, $time,$domain, $s);
	}
}
?>