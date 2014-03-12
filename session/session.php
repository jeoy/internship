<?php
       
 
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
		return $result;
	}
	function make_single_request($table,$key_column,$key_name,$target_column) {
		$query="select * from ".$table." where ".$key_column." = "."'$key_name'";
		//echo $query; //select * from user where id = 'user1'
		$result=mysql_query($query);
		$row=mysql_fetch_array($result);
		//echo $row[$target_column];
		//return $row;
		return $row[$target_column];
	}
}

class key extends database {
	var $key_value;
	var $key_file_path;
    var   $memcache;
 
	function key() 
    {
         $memcache = new Memcache;
         $memcache->connect('localhost', 11211) or die ("Could not connect");    
           $date_r= @date("y-m-d", $memcache->get('time'));     
           $key_r=    $memcache->get('key');   
		 
		
		//read file
		//$fp_r=fopen($this->key_file_path,'r');//$fp_r=fopen("./gloable_variable.txt",'r');//full path:/Applications/XAMPP/xamppfiles/htdocs/session
		//$date_r=@date("y-m-d",fgets($fp_r));
		//$key_r=fgets($fp_r);
		
		//get time
		$time=time();
		$date_g=date("y-m-d",$time);//date("Y-m-d H:i:s");//echo $date.$time;
		
		//make comparison and write file
		if($date_r==$date_g) {//echo 1;
			$this->key_value=$key_r;
		} else {//echo 2;
			$this->database();
			//if($this->err_no) return; //echo "error! error no.:".$this->err_no;
			$this->key_value=$this->make_single_request("constant","name","key","value");
			/* v.2
			 * $row=$this->make_single_request("constant","name","key","value");
			 * $this->key_value=$row['value'];
			 */
			/* v.1
			  $query="select * from constant where name = 'key'";
			  echo $query;
			  $result=mysql_query($query);
			  $row=mysql_fetch_array($result);
			  $this->key_value=$row['value'];
			 */
	 $memcache = new Memcache;
     $memcache->connect('localhost', 11211) or die ("Could not connect");	        
    $memcache->set('key', $this->key_value, false,0) or die ("Failed to save data at the server"); 
    $memcache->set('time', $time, false,0) or die ("Failed to save data at the server"); 
                    
            //$fp_w=fopen($this->key_file_path,'w+');
			//fwrite($fp_w, $time."\n");
			//fwrite($fp_w, $this->key_value);
		}
	}
}

class session extends database {
	//if cookie is set (wheter login or not) check whether its valid
	//if cookie is not set, set one
	var $os;
	var $cookie;
	var $expire;

	var $control;
	var $microtime;
	var $date;
	var $key_value;

	var $islogin;
	var $isset;
	var $isvalid;
	
	var $userpasswd;

	function session($os,$control,$userid,$userpasswd) {
		$this->database();
		$key=new key();
		$this->key_value=$key->key_value;
		
		$this->os=$os;
		$this->control=$control;
		
		$this->isvalid=(isset($_COOKIE['Session_ID']) and isset($_COOKIE['Token_Date']) and isset($_COOKIE['Token_ID']) );
		if(!$this->isvalid) {
			$this->control=0;
			$this->isset=0;
			$this->islogin=0;
			$this->set_cookie($userid,$userpasswd);
		} else {
			$this->islogin=isset($_COOKIE['User_ID']);//isset($_COOKIE['User_ID']) and (1-isset($_COOKIE['Session_ID']));
			//have some problem here
			$this->isset=isset($_COOKIE['Session_ID']) or isset($_COOKIE['User_ID']);//$this->islogin or ( (1-isset($_COOKIE['User_ID'])) and isset($_COOKIE['Session']) );
			//$this->isvalid=isset($_COOKIE['User_ID']) and isset($_COOKIE['Session_ID']); if($this->isvalid) return;
			
			$this->microtime=microtime();
			$this->date=intval(substr($this->microtime,11,10));//$this->date=substr(date("YmdHis"), 2);//date("y-m-d",time());
			$this->microsecond=floatval(substr($this->microtime,0,5));
		
			switch ($this->control) {
				case 0: //refresh
					$this->isvalid=$this->compare();
					break;
				case 1: //login
					$this->set_cookie($userid,$userpasswd);
					break;
				default: //logout
					$this->reset();
					break;
			}
			if(!$this->isvalid) {
				$this->control=0;
				$this->isset=0;
				$this->islogin=0;
				$this->set_cookie($userid,$userpasswd);
			}
		}
	}
	function set_cookie($userid,$userpasswd) {
		$this->cookie=array(
				'Session_ID'=>"",
				'Token_Date'=>"",
				'User_ID'=>"",
				'Token_ID'=>"");
		//preset
		$this->expire=time()+3600;

		//Token_Date
		$this->cookie['Token_Date']=date("Ymd",$this->date); //"YmdHis"
		
		//Session_ID and User_ID and Token_ID
		switch ($this->control) {
			case 0: //refresh
				//Session_ID
				$this->cookie['Session_ID']=$this->cal_session_id();
				//User_ID: dont set
				//Token_ID
				$this->cookie['Token_ID']=$this->count_md5();
				//
				$this->isset=1;
				$this->islogin=0;
				$this->isvalid=1;
				break;
			case 1: //login
				//User_ID
				$this->cookie['User_ID']=$userid;
				//Token_ID and Session_ID
				$this->userpasswd=$userpasswd;
				$this->isvalid=$this->compare(); //count md5 to compare, if notvalid, should calculate Session_ID again, no matter valid, calculate Token_ID
				if(!$this->isvalid){
                 $this->cookie['Session_ID']=$this->cal_session_id();
                                    }
                else
                { 
                    $this->cookie['Session_ID']=$_COOKIE['Session_ID'];
                 }
                $this->cookie['Token_ID']=$this->count_md5(); //count md5 to set Token_ID
				//
				$this->isset=1;
				$this->islogin=1;
				$this->isvalid=1;
				break;
			default: //logout
				break;
		}
		
		//print_r($this->cookie);
		
		//output
		//set cookie //$this->set_cookie_immediate("",$this->cookie);
		$this->output();
		
		/*
		  setcookie("Session_ID",$this->cookie['Session_ID'],$this->expire);
		  setcookie("Token_Date",$this->cookie['Token_Date'],$this->expire);
		  setcookie("User_ID",$this->cookie['User_ID'],$this->expire);
		  setcookie("Token_ID",$this->cookie['Token_ID'],$this->expire);
		*/
	}
	function compare() {
		switch ($this->control) {
			case 0: //refresh
				if($this->islogin) {
					//with userid and userpasswd
					//Token_ID=Session_ID+Token_Date+key_today+User_ID+userpasswd
					//should query password(md5) here
					$userpasswd=$this->make_single_request("user", "id", $_COOKIE['User_ID'], "passwd");
					$md5=md5($_COOKIE['Session_ID'] . $_COOKIE['Token_Date'] . $this->key_value . $_COOKIE['User_ID']. $userpasswd);
				} else {
					//without userid and passwd
					//Token_ID=Session_ID+yyyymmdd+key_today
					$md5=md5($_COOKIE['Session_ID'] . $_COOKIE['Token_Date'] . $this->key_value);
				}
				break;
			case 1: //login
				$md5=md5($_COOKIE['Session_ID'] . $_COOKIE['Token_Date'] . $this->key_value);
				break;
			default: //logout
				break;
		}
		//$this->isvalid=($md5==$_COOKIE['Token_ID'])?1:0;
		return ($md5==$_COOKIE['Token_ID']);
	}
	function cal_session_id() {
		//have no need for inputs other than $_SERVER
		//format:yymddhhssmmm(11)+mac(12)+port(4)+random(3)+count(2)
		
		//time 11bit
		$time=dechex(($this->date+$this->microtime)*1000);
		$time_11bit=substr($time."00000",0,11);
		//mac 12bit
		$mac=new GetMacAddr($this->os); //echo $mac->mac_addr; //98:fe:94:40:77:ea
		$mac_12bit=substr($mac->mac_addr,0,2) . substr($mac->mac_addr,3,2) . substr($mac->mac_addr,6,2) . substr($mac->mac_addr,9,2) . substr($mac->mac_addr,12,2) . substr($mac->mac_addr,15,2);
		//port 4bit
		$port=dechex($_SERVER['REMOTE_PORT']);
		$port_4bit=substr($port."0000",0,4);
		//random 3bit
		$random=rand(0,999);
		if($random<10) $random_3bit="00".$random;
		else if($random<100) $random_3bit="0".$random;
		else $random_3bit=$random;
		//count 2bit
		$count_2bit="00";
		
		//$this->cookie['Session_ID']=$time_11bit . $mac_12bit . $port_4bit . $random_3bit . $count_2bit;
		return $time_11bit . $mac_12bit . $port_4bit . $random_3bit . $count_2bit;
	}
	function count_md5() {
		switch ($this->control) {
			case 0: //refresh
				//without userid and passwd
				//Token_ID=Session_ID+yyyymmdd+key_today
				$Token_ID=md5($this->cookie['Session_ID'] . $this->cookie['Token_Date']. $this->key_value);
				//
				$this->isset=1;
				$this->islogin=0;
				$this->isvalid=1;
				break;
			case 1: //login
				//count md5 to set cookie
				//with userid and passwd
				//Token_ID=Session_ID+yyyymmdd+key_today+User_ID+userpasswd
				//the '$this->date' here has some different from discribed before
				$Token_ID=md5($this->cookie['Session_ID'] . $this->cookie['Token_Date']. $this->key_value . $this->cookie['User_ID'] . $this->userpasswd);
				//
				$this->isset=1;
				$this->islogin=1;
				$this->isvalid=1;
				break;
			default: //logout
				break;
		}
		return $Token_ID;
	}
	function reset() {
		//Session,Token_Date,Token_ID,User_ID
		$this->cookie=array(
				'Session_ID'=>"",
				'Token_Date'=>"",
				'User_ID'=>"",
				'Token_ID'=>"");
		$this->expire=time()-60;
		$this->set_cookie_immediate("",$this->cookie,$this->expire);
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
	function output() {
		$this->set_cookie_immediate("",$this->cookie);
	}
}

class refresh extends session {
	function refresh($os) {
		$control=0;
		$this->session($os,$control,"","");
	}
}

class login extends session {
	var $issecure;
	var $issucceed;
	function login($os,$userid,$userpasswd) {
		$this->check_secure($userid, $userpasswd);
		if(!$this->issecure) return;
		$control=1;
		$this->database(); //ATTENTION
		$passwd=$this->make_single_request("user", "id", $userid, "passwd");
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

class logout extends session {
	function logout($os) {
		$control=-1;
		$this->session($os,$control,"","");
	}
}

class GetMacAddr {
	var $return_array = array();
	var $mac_addr;
	 
	function GetMacAddr($os_type) {
		switch ( strtolower($os_type) ) {
			case "linux":
				$this->forLinux();
				break;
			case "solaris":
				break;
			case "unix":
				break;
			case "aix":
				break;
			default:
				$this->forWindows();
				break;
		}

		$temp_array = array();
		foreach ( $this->return_array as $value ) {
			if ( preg_match( "/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i", $value, $temp_array ) )
			{
				$this->mac_addr = $temp_array[0];
				break;
			}
		}
		unset($temp_array);
		return $this->mac_addr;
	}
	function forWindows() {
		@exec("ipconfig /all", $this->return_array);
	 	if ( $this->return_array )
	 		return $this->return_array;
	 	else {
	 		$ipconfig = $_SERVER["WINDIR"]."system32ipconfig.exe";
			if ( is_file($ipconfig) )
				@exec($ipconfig." /all", $this->return_array);
			else
				@exec($_SERVER["WINDIR"]."systemipconfig.exe /all", $this->return_array);
			return $this->return_array;
		}
	}
	function forLinux() {
		@exec("ifconfig -a", $this->return_array);
		return $this->return_array;
	}
}

$os="linux";
 
 
?>