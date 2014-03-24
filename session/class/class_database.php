<?php

include_once '../global_variable/global_variable.php';

class database {
	var $servername="localhost";
	var $username="root";
	var $dbpasswd="";
	var $db_name="tootoo";
	var $con;
	var $result;
	var $err;
	var $err_no;

	function database() {
		$this->make_connection();
		$this->select_db();
	}
	//make connection to database
	function make_connection() {
		$this->con=mysql_connect($this->servername,$this->username,$this->dbpasswd);
		if (!$this->con) {
			$this->err=mysql_error();
			$this->err_no=101;
			die('Could not connect: ' . mysql_error());
			return;
		}
	}
	//select database
	function select_db() {
		$this->result=mysql_select_db($this->db_name, $this->con);
		if (!$this->result) {
			$this->err=mysql_error();
			$this->err_no=102;
			die('Could not select: ' . mysql_error());
			return;
		}
	}
	function make_query($query) {
		$result=mysql_query($query);
		//return $result;
		if (!$result) {
			$this->err=mysql_error();
			$this->err_no=103;
			die('Could not query: ' . mysql_error());
			return "";
		} else {
			return $result;
		}
	}
	function make_single_request($table,$key_column,$key_name,$target_column) {
		$query="select * from ".$table." where ".$key_column." = "."'$key_name'";
		//echo $query; //select * from user where id = 'user1'
		$result=mysql_query($query);
		if (!$result) {
			$this->err=mysql_error();
			$this->err_no=104;
			die('Could not query: ' . mysql_error());
			return "";
		} else {
			$row=mysql_fetch_array($result);
			//echo $row[$target_column];
			//return $row;
			return $row[$target_column];
		}
	}
}
?>