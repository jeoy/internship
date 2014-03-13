<?php

include_once '../class/class_session.php';

class refresh extends session {
	function refresh($os) {
		$control=0;
		$this->session($os,$control,"","");
	}
}

?>