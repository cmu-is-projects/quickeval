<?php

// constants used by class
define('MYSQL_TYPES_NUMERIC', 'int real ');
define('MYSQL_TYPES_DATE', 'datetime timestamp year date time ');
define('MYSQL_TYPES_STRING', 'string blob ');

/**
 * MySQL Database Class
 *
 * @version 1.0.6
 * @package QuickEval
 * @author Ari Rubinstein
 **/
class DBClass {

	/**
	 * holds the last error. Usually mysql_error()
	 *
	 * @var string
	 **/
	var $last_error;
	
	/**
	 * holds the last query executed.
	 *
	 * @var string
	 **/
	var $last_query;
	
	/**
	 * holds the last number of rows from a select
	 *
	 * @var string
	 **/
	var $row_count;


	/**
	 * mySQL host to connect to
	 *
	 * @var string
	 **/
	var $host;
	
	/**
	 * mySQL user name
	 *
	 * @var string
	 **/
	var $user; 
	
	/**
	 * mySQL password
	 *
	 * @var string
	 **/
	var $pw;
	
	/**
	 * mySQL database to select
	 *
	 * @var string
	 **/
	var $db;

	/**
	 * current/last database link identifier
	 *
	 * @var string
	 **/
	var $db_link;
	
	/**
	 * add/strip slashes when it can
	 *
	 * @var boolean
	 **/
	var $auto_slashes;


	/**
	 * class constructor.  Initializations here.
	 *
	 * Setup your own default values for connecting to the database here. You
	 * can also set these values in the connect() function and using
	 * the select_database() function.
	 * 
	 * @return void
	 * @author Ari Rubinstein
	 **/
	function DBClass() {
		$this->host = 'localhost';
		$this->user = '';
		$this->pw = '';
		$this->db = '';

		$this->auto_slashes = true;
	}


	/**
	 * Opens a connection to MySQL and selects the database.  If any of the
	 * function's parameter's are set, we want to update the class variables. 
	 * 
	 * If they are NOT set, then we're going to use the currently existing
	 * class variables.
	 *  
	 * @return boolean true if successful, false if there is failure.  
	 * @author Ari Rubinstein
	 **/
	function connect($host='', $user='', $pw='', $db='', $persistant=true) {

		if (!empty($host)) $this->host = $host; 
		if (!empty($user)) $this->user = $user; 
		if (!empty($pw)) $this->pw = $pw; 


		// Establish the connection.
		if ($persistant) 
			$this->db_link = mysql_pconnect($this->host, $this->user, $this->pw);
		else 
			$this->db_link = mysql_connect($this->host, $this->user, $this->pw);

		// Check for an error establishing a connection
		if (!$this->db_link) {
			$this->last_error = mysql_error();
			return false;
		} 

		// Select the database
		if (!$this->select_db($db)) return false;

		return $this->db_link;  // success
	}

	/**
	 * Selects the database for use.  If the function's $db parameter is 
	 * passed to the function then the class variable will be updated.
	 *
	 * @return boolean true if successful, false otherwise
	 * @author Ari Rubinstein
	 **/
	function select_db($db='') {
		if (!empty($db)) $this->db = $db; 
		if (!mysql_select_db($this->db)) {
			$this->last_error = mysql_error();
			return false;
		}

		return true;
	}
	


	/**
	 * Selects rows based on input query and returns an array of associative arrays of the results
	 *
	 * @param $sql string The SQL query to run
	 * @return mixed array of rows if query was successful, false otherwise
	 * @author Ari Rubinstein
	 **/
	function select_return_rows($sql){
		$t = $this->select($sql);
		if (!$t){
			return false;
		} else {
			$outarr = array();
			while ($row=$this->get_row($t, 'MYSQL_ASSOC')){
				$outarr[] = $row;
			}
			return $outarr;
		}
	}
	
	/**
	 * Selects a single row based on input query and returns an associative array of the results
	 *
	 * @param $sql string The SQL query to run
	 * @return mixed associative array of row if query was successful, false otherwise
	 * @author Ari Rubinstein
	 **/
	function select_return_row($sql){
		$t = $this->select($sql);
		if (!$t){
			return false;
		} else {
			$row=$this->get_row($t, 'MYSQL_ASSOC');
			return $row;
		}
	}


	/**
	 * Performs an SQL query
	 *
	 * @param $sql string The SQL query to run
	 * @return mixed the result pointer or false if there is an error
	 * @author Ari Rubinstein
	 **/
	function select($sql) {
		$this->last_query = $sql;
		$r = mysql_query($sql);
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}
		$this->row_count = mysql_num_rows($r);
		return $r;
	}

	/**
	 * Performs an SQL query and returns the count of records
	 *
	 * @param $sql string The SQL query to run
	 * @return mixed the row count if query was successful, false otherwise
	 * @author Ari Rubinstein
	 **/
	function select_count($sql) {
		$this->last_query = $sql;
		$r = mysql_query($sql);
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}
		$this->row_count = mysql_num_rows($r);
		mysql_free_result($r);
		return $this->row_count;
	}

	/**
	 * Performs an SQL query
	 *
	 * @param $sql string The SQL query to run
	 * @return boolean true if success, returns false otherwise
	 * @author Ari Rubinstein
	 **/
	function query($sql) {
		// Performs an SQL query
		// 
		$this->last_query = $sql;
		$r = mysql_query($sql);
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}
		return true;
	}


	/**
	 * Performs an SQL query with the assumption that only ONE column and one result are to be returned.
	 *
	 * @return mixed the single result from the query, false otherwise
	 * @author Ari Rubinstein
	 **/
	function select_one($sql) {
		$this->last_query = $sql;
		$r = mysql_query($sql);
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}
		if (mysql_num_rows($r) > 1) {
			$this->last_error = "Your query in function select_one() returned more that one result.";
			return false;     
		}
		if (mysql_num_rows($r) < 1) {
			$this->last_error = "Your query in function select_one() returned no results.";        
			return false;
		}
		$ret = mysql_result($r, 0);
		mysql_free_result($r);
		if ($this->auto_slashes) return stripslashes($ret);
		else return $ret;
	}

	/**
	 * Returns true if a single row exists based on SQL query
	 *
	 * @param $sql string the SQL string to evaluate
	 * @return boolean true if a single row exists, false otherwise or if error
	 * @author Ari Rubinstein
	 **/
	function single_row_exists($sql) { 
		$this->last_query = $sql;      
		$r = mysql_query($sql);
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}
		if (mysql_num_rows($r) > 1) {
			$this->last_error = "Your query in function row_exists() returned more that one result.";
			return false;     
		}
		if (mysql_num_rows($r) < 1) {
			$this->last_error = "Your query in function row_exists() returned no results.";        
			return false;
		}      
		return true;
	}

	function get_row($result, $type='MYSQL_BOTH') { 
		// Returns a row of data from the query result.  You would use this
		// function in place of something like while($row=mysql_fetch_array($r)). 
		// Instead you would have while($row = $db->get_row($r)) The
		// main reason you would want to use this instead is to utilize the
		// auto_slashes feature.      
		if (!$result) {
			$this->last_error = "Invalid resource identifier passed to get_row() function.";
			return false;  
		}      
		if ($type == 'MYSQL_ASSOC') $row = mysql_fetch_array($result, MYSQL_ASSOC);
		if ($type == 'MYSQL_NUM') $row = mysql_fetch_array($result, MYSQL_NUM);
		if ($type == 'MYSQL_BOTH') $row = mysql_fetch_array($result, MYSQL_BOTH);       
		if (!$row) return false;
		if ($this->auto_slashes) {
			// strip all slashes out of row...
			foreach ($row as $key => $value) {
				$row[$key] = stripslashes($value);
			}
		}
		return $row;
	}

	function dump_query($sql) {   
		// Useful during development for debugging  purposes.  Simple dumps a 
		// query to the screen in a table. 
		$r = $this->select($sql);
		if (!$r) return false;
		echo "<div style=\"border: 1px solid blue; font-family: sans-serif; marg in: 8px;\">\n";
		echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";

		$i = 0;
		while ($row = mysql_fetch_assoc($r)) {
			if ($i == 0) {
				echo "<tr><td colspan=\"".sizeof($row)."\"><span style=\"font-face: monospace; font-size: 9pt;\">$sql</span></td></tr>\n";
				echo "<tr>\n";
				foreach ($row as $col => $value) {
					echo "<td bgcolor=\"#E6E5FF\"><span style=\"font-face: sans-serif; font-size: 9pt; font-weight: bold;\">$col</span></td>\n";
				}
				echo "</tr>\n";
			}
			$i++;
			if ($i % 2 == 0) $bg = '#E3E3E3';
			else $bg = '#F3F3F3';
			echo "<tr>\n";
			foreach ($row as $value) {
				echo "<td bgcolor=\"$bg\"><span style=\"font-face: sans-serif; font-size: 9pt;\">$value</span></td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table></div>\n";
	}

	function insert_sql($sql) {       
		// Inserts data in the database via SQL query.
		// Returns the id of the insert or true if there is not auto_increment
		// column in the table.  Returns false if there is an error.       
		$this->last_query = $sql;      
		$r = mysql_query($sql);
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}      
		$id = mysql_insert_id();
		if ($id == 0) return true;
		else return $id; 
	}

	function update_sql($sql) { 
		// Updates data in the database via SQL query.
		// Returns the number or row affected or true if no rows needed the update.
		// Returns false if there is an error.
		$this->last_query = $sql;      
		$r = mysql_query($sql);
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}      
		$rows = mysql_affected_rows();
		if ($rows == 0) return true;  // no rows were updated
		else return $rows;      
	}

	/**
	 * Inserts a row into the database from key->value pairs in an array. The
	 * array passed in $data must have keys for the table's columns. You can
	 * not use any MySQL functions with string and date types with this 
	 * function.  You must use insert_sql for that purpose.
	 * 
	 * @return mixed the id of the insert or true if there is not auto_increment column in the table.  Returns false if there is an error.
	 * @author Ari Rubinstein
	 **/
	function insert_array($table, $data) {      
   
		if (empty($data)) {
			$this->last_error = "You must pass an array to the insert_array() function.";
			return false;
		}      
		$cols = '(';
		$values = '(';      
		foreach ($data as $key=>$value) {     // iterate values to input          
			$cols .= "$key,";           
			$col_type = $this->get_column_type($table, $key);  // get column type
			if (!$col_type) return false;  // error!
			// determine if we need to encase the value in single quotes
			if (is_null($value)) {
				$values .= "NULL,";   
			} 
			elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
				$values .= "$value,";
			}
			elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
				$value = $this->sql_date_format($value, $col_type); // format date
				$values .= "'$value',";
			}
			elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
				if ($this->auto_slashes) $value = addslashes($value);
				$values .= "'$value',";  
			}
		}
		$cols = rtrim($cols, ',').')';
		$values = rtrim($values, ',').')';     
		// insert values
		$sql = "INSERT INTO $table $cols VALUES $values";
		return $this->insert_sql($sql);

	}

	function update_array($table, $data, $condition) {
		// Updates a row into the database from key->value pairs in an array. The
		// array passed in $data must have keys for the table's columns. You can
		// not use any MySQL functions with string and date types with this 
		// function.  You must use insert_sql for that purpose.
		// $condition is basically a WHERE claus (without the WHERE). For example,
		// "column=value AND column2='another value'" would be a condition.
		// Returns the number or row affected or true if no rows needed the update.
		// Returns false if there is an error.
		if (empty($data)) {
			$this->last_error = "You must pass an array to the update_array() function.";
			return false;
		}
		$sql = "UPDATE $table SET";
		foreach ($data as $key=>$value) {     // iterate values to input
			$sql .= " $key=";  
			$col_type = $this->get_column_type($table, $key);  // get column type
			if (!$col_type) return false;  // error!

			// determine if we need to encase the value in single quotes
			if (is_null($value)) {
				$sql .= "NULL,";   
			} 
			elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
				$sql .= "$value,";
			}
			elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
				$value = $this->sql_date_format($value, $col_type); // format date
				$sql .= "'$value',";
			}
			elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
				if ($this->auto_slashes) $value = addslashes($value);
				$sql .= "'$value',";  
			}
		}
		$sql = rtrim($sql, ','); // strip off last "extra" comma
		if (!empty($condition)) $sql .= " WHERE $condition";
		// insert values
		return $this->update_sql($sql);
	}


	/**
	 * Executes SQL commands from an external file
	 *
	 * @return boolean result of the sql loaded from file
	 * @author Ari Rubinstein
	 **/
	function execute_file ($file) {
		// executes the SQL commands from an external file.
		if (!file_exists($file)) {
			$this->last_error = "The file $file does not exist.";
			return false;
		}
		$str = file_get_contents($file);
		if (!$str) {
			$this->last_error = "Unable to read the contents of $file.";
			return false; 
		}
		$this->last_query = $str; 
		// split all the query's into an array
		$sql = explode(';', $str);
		foreach ($sql as $query) {
			if (!empty($query)) {
				$r = mysql_query($query);

				if (!$r) {
					$this->last_error = mysql_error();
					return false;
				}
			}
		}
		return true;
	}

	function get_column_type($table, $column) {
		// Gets information about a particular column using the mysql_fetch_field
		// function.  Returns an array with the field info or false if there is
		// an error.
		$r = mysql_query("SELECT $column FROM $table");
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}
		$ret = mysql_field_type($r, 0);
		if (!$ret) {
			$this->last_error = "Unable to get column information on $table.$column.";
			mysql_free_result($r);
			return false;
		}
		mysql_free_result($r);
		return $ret;
	}

	/**
	 * Converts a SQL date to a php timestamp (month/day/year)
	 *
	 * @param $value string MySQL sql date
	 * @return string the date formatted in month/day/year format
	 * @author Ari Rubinstein
	 **/
	function sqldate_to_php($value){
		if (gettype($value) == 'string') $value = strtotime($value);
		return date("m/d/Y", $value);
	}
	/**
	 * Converts a php date to a sql timestamp (year-month-day)
	 *
	 * @param $value string php date
	 * @return string the date formatted in year-month-day format (MySQL)
	 * @author Ari Rubinstein
	 **/
	function phpdate_to_sql($value){
		return $this->sql_date_format($value);
	}

	/**
	 * Returns the date in a format for input into the database.  You can pass
	 * this function a timestamp value such as time() or a string value
	 * such as '04/14/2003 5:13 AM'. 
	 * @return string timestamp formatted in year-month-day Hour:minute:second format
	 * @author Ari Rubinstein
	 **/
	function sql_date_format($value) {
		if (gettype($value) == 'string') $value = strtotime($value);
		return date('Y-m-d H:i:s', $value);
	}

	/**
	* Prints the last error in a nice-looking formatted box
	* If $show_query is true, then the last query that was executed will
	* be displayed as well.
	* @return void
	* @author Ari Rubinstein
	**/
	function print_last_error($show_query=true) {
		?>
		<div style="border: 1px solid red; font-size: 9pt; font-family: monospace; color: red; padding: .5em; margin: 8px; background-color: #FFE2E2">
			<span style="font-weight: bold">db.class.php Error:</span><br><?= $this->last_error ?>
      </div>
      <?php
      if ($show_query && (!empty($this->last_query))) {
      $this->print_last_query();
      }
 
   }

   /**
    * Prints the last query in a nicely formatted box
    *
    * @return void
    * @author Ari Rubinstein
    **/
   function print_last_query() {
    
      // Prints the last query that was executed to the screen in a nicely formatted
      // box.
     
      ?>
      <div style="border: 1px solid blue; font-size: 9pt; font-family: monospace; color: blue; padding: .5em; margin: 8px; background-color: #E6E5FF">
         <span style="font-weight: bold">Last SQL Query:</span><br><?php echo str_replace("\n", '<br>', $this->last_query) ?>
      </div>
      <?php  
   }

	/******************************
	* Extensions
	******************************/
	/**
	 * Static function to initiate and return the DB object
	 *
	 * @return Object the initiated DB object
	 * @author Ari Rubinstein
	 **/
	public static function start($dbs=DB_SERVER,$dbp=DB_PORT,$dbu=DB_USER,$dbpass=DB_PASS,$dbd=DB_DATABASE){
		$db = new DBClass();
		$db->connect($dbs.":".$dbp, $dbu, $dbpass, $dbd) or die("Could not connect to database");
		return $db;
	}

	/**
	* Creates a string that is in the form of a sql argument
	* aka arg("abc", "123", "<=") = `abc` <= 123
	*/
	public static function arg($name, $value, $operation = "="){
		$db = DBClass::Start();
		$q = '"';
		if (is_int($value)){
			$q = '';
		}
		return "`$name` $operation $q".$db->s($value)."$q ";
	}
	
	/**
	 * returns the last error from the DB class
	 *
	 * @return string the last error from the DB class
	 * @author Ari Rubinstein
	 **/
	function get_last_error(){
		return $this->last_error;
	}
	
	/**
	 * shortcut to mysql_real_escape_string
	 *
	 * @param $data string the data to escape
	 * @return string the escaped data
	 * @author Ari Rubinstein
	 **/
	function s($data){
		return mysql_real_escape_string($data);
	}

	/**
	 * Runs a query and returns a string containing an html table
	 *
	 * @param $query the query to run and put in a table
	 * @return string the HTML table with the SQL query result in it
	 * @author Ari Rubinstein
	 **/
	function query_to_table($query){
		$ct = "";
		$r = $this->select($sql);
		if (!$r) return false;
		$ct .= "<table>";
		$count = 0;
		while($row=$db->get_row($r, "MYSQL_ASSOC")){
			if ($count++ == 0){
				$ct .= "<thead>";
				$ct .= "<tr>";
				foreach ($row as $key => $var){
					$ct .= "<th>$key</th>";
				}
				$ct .= "</tr>";
				$ct .= "</thead>";
			}
			$ct .= "<tr>";
			foreach ($row as $key => $var){
				$ct .= "<td>$var</td>";
			}
			$ct .= "</tr>";
		}
		$ct .= "</table>";
		return $ct;
	}

}

?>