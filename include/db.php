<?php
//------Credit the author by not removing this --------------------------------------------------
//												                                                    |
//		Author 		:		MASTERMIND 8427780001					                                        |
//		Email 		:		 			                                    |
//			                                    |
//												                                                    |
//-----------------------------------------------------------------------------------------------
//error_reporting(0);
date_default_timezone_set(isset($conf['tz'])?$conf['tz']:'UTC');
class mysqliDB {
	public $CON = false;
	public $CLOSE;
	public $ERROR = false;
	function __construct($opt=null) {
		global $DBCON;
		if($opt!==null && isset($opt['host']) && isset($opt['user']) && isset($opt['pass']) && isset($opt['name'])) $db = $opt;
		else $db = $DBCON;
		$this->CON = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']) or die($this->error('connect'));
		if($this->CON == false) $this->CON = NULL;
		$this->CLOSE = true;
	}
	function query($q) {
		return mysqli_query($this->CON, $q) or die($this->error('query', $q));
	}
	function fetch($q) {
		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));
		$return = array();
		while($row = (mysqli_fetch_assoc($result))) {
			array_push($return, $row);
		}
		return $return;
	}
	function select($table,$params,$condition=1)

	{

		$q="select $params from $table where $condition";

		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));

		$return = array();

		while($row = (mysqli_fetch_assoc($result))) {

			array_push($return, $row);

		}

		return $return;

	}
	
	function select_order($table,$params,$condition=1,$order)

	{

		$q="select $params from $table where $condition order by $order";

		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));

		$return = array();

		while($row = (mysqli_fetch_assoc($result))) {

			array_push($return, $row);

		}

		return $return;

	}
	function insert_multiple($table,$keys,$val) {

		$out='';

		$rows = sizeof($val[0]);

		$cols = sizeof($keys);

		for($i=0;$i<$rows;$i++) {

			$out.='(';

			for($j=0;$j<$cols;$j++) {			

				$out.="'".$val[$j][$i]."'";

				if($j<($cols-1)) $out.=', ';			

			}

			$out.=')';

			if($i<($rows-1)) $out.=', ';

		}

		$keys_str = implode(', ', $keys);

		$q="INSERT INTO $table($keys_str) VALUES $out";

		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));

		return $result;

	}
	function insert($table,$keys,$val) {
		$out='';
		$rows = sizeof($val[0]);
		$cols = sizeof($keys);
		for($i=0;$i<$rows;$i++) {
			$out.='(';
			for($j=0;$j<$cols;$j++) {			
				$out.="'".$val[$j][$i]."'";
				if($j<($cols-1)) $out.=', ';			
			}
			$out.=')';
			if($i<($rows-1)) $out.=', ';
		}
		$keys_str = implode(', ', $keys);
		$q="INSERT INTO $table($keys_str) VALUES $out";
		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));
		return $result;
	}
	function insert_single($table,$keys,$val) {

		$keys_str = implode(', ', $keys);
		$values_str = implode(', ',$val);

		$q="INSERT INTO $table($keys_str) VALUES ($values_str)";

		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));

		return $result;

	}
	function update($table,$upd,$condition=1)

	{

		$q= "update $table set $upd where $condition";

		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));

		return $result;

	}
	
	function delete($table,$condition=1)

	{

		$q= "delete from $table where $condition";

		$result = mysqli_query($this->CON, $q) or die($this->error('query', $q));

		return $result;

	}
	
	function param($param, $type=NULL, $encode=true) {
		$param = isset($_REQUEST[$param])?mysqli_real_escape_string($this->CON, $_REQUEST[$param]):NULL;
		if($type==='i') $param = ($param!==NULL)?((int)$param):NULL;
		elseif($type==='f') $param = ($param!==NULL)?((float)$param):NULL;
		if($param!==NULL && $encode) return htmlspecialchars($param);
		else return $param;
	}
	function insert_id() {
		return mysqli_insert_id($this->CON);
	}
  public function esc($str) {
    return mysqli_real_escape_string($this->CON, htmlspecialchars($str));
  }
	private function error($err, $q ='') {
		$this->ERROR = true;
		if($err == 'query') {
			return '{"status":false, "error":"dbquery", "errorcode":"'.mysqli_error($this->CON).'", "query":"'.$q.'"}';
			exit;
		}
		db_error($err);
	}
	function closeConnection() {
		if($this->CON !== NULL && $this->CLOSE == true) {
			mysqli_close($this->CON);
		}
	}
	function __destruct() {
		$this->closeConnection();
	}
}
function db_error($err) {
	return '{"status":false, "error":"dbconn", "errorcode":"'.mysqli_connect_error().'"}';
	exit;
}
?>
