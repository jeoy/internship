<?php

include_once '../class/class_session.php';

class logout extends session {
	function logout($os) {
		$control=-1;
		$this->session($os,$control,"","");
	}
}

?>