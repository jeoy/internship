<?php

include_once '../class/class_database.php';

class key extends database {
	var $key_value;
	var $key_file_path;
    var $memcache;
 
	function key() {
		//$this->key_value="139650375";


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
?>