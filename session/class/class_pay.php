<?php 
	include_once '../class/class_database.php';
	
	class pay extends database {
		var $cps_id;
		var $cps_number;
		function pay($cps_id) {
			$this->cps_id=$cps_id;
			$this->database();
			$this->cps_number=$this->make_single_request("cps", "cps_id", $this->cps_id, "cps_number");
			if($this->err_no==104) {
				//cannot get cps_id
				//not working, dont know why
			} else {
				$this->cps_number++;
				$this->make_query("update cps set cps_number = "."'$this->cps_number'"." where cps_id = "."'$this->cps_id'");
			}
		}
	}
?>