<?php

include_once '../class/class_session.php';

class login extends session {
	var $issecure;
	var $issucceed;
	function login($os,$userid,$userpasswd) {
		$this->check_secure($userid, $userpasswd);
		if(!$this->issecure) return;
		$control=1;
		$this->database=new database();//$this->database(); //ATTENTION
		$passwd=$this->database->make_single_request("user", "id", $userid, "passwd");
		$this->issucceed=($passwd==$userpasswd);
		if($this->issucceed) {
			$this->session($os,$control,$userid,$userpasswd);
		} else {
			$this->session($os,$control,"","");
		}
	}
	function check_secure($userid,$userpasswd) {
		if(empty($userid) or empty($userpasswd)) $this->issecure=0;
		else $this->issecure=1;
	}
}

?>