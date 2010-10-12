<?php
/**
 * This class is the core CRUD class that all other OOP classes
 * are based off of. 
 *
 * @package QuickEval
 * @author Ari Rubinstein
 **/
require_once("config.php");

abstract class CustomClass {
	/**
	 * Returns a string of the corresponding Table Name
	 *
	 * @return String The name of the SQL table this class corresponds with 
	 * @author Ari Rubinstein
	 **/
	abstract protected function tablename();

	/**
	 * Returns allowed parameter names
	 *
	 * @return Array the array of strings of allowed_parameters
	 * @author Ari Rubinstein
	 **/
	abstract protected function allowed_parameters();
	
	/**
	 * Returns names of parameters that must be set
	 *
	 * @return Array the array of names of required parameters
	 * @author Ari Rubinstein
	 **/
	abstract protected function required_parameters();
	
	/**
	 * Returns array of default parameters and values
	 *
	 * @return Array the array of default parameters and values
	 * @author Ari Rubinstein
	 **/
	abstract protected function default_parameters();
	
	/**
	 * Returns array of errors for parameters
	 *
	 * @return Array array of errors for parameter.  If there are no errors, returns true
	 * @param string $name the name of the parameter to validate
	 * @param string $value the value to verify
	 * @author Ari Rubinstein
	 **/
	abstract public function validate_parameter($name, $value);

	/**
	 * The array of parameters the object will store
	 *
	 * @var Array parameters the object will store
	 **/
	protected $params = array();
	
	/**
	 * List of errors from last validation
	 *
	 * @var Array the array of errors from the last validation
	 **/
	public $errors = array();
	
	/**
	 * Returns the array of errors from the last validation
	 *
	 * @return array the array of errors
	 * @author Ari Rubinstein
	 **/
	public function get_errors(){
		return $this->errors;
	}
	
	/**
	 * Automatically fetches object with provided ID
	 *
	 * @return void
	 * @author Ari Rubinstein
	 **/
	public function __construct($id = null){
		if ($id != null){
			if (!$this->load($id)){
				throw new Exception("Could not load object from " . $this->tablename() . " id #$id");
			}
		} else {
			$this->load_variables($this->default_parameters());
			return true;
		}
		
	}

	/**
	 * Loads variables from a resultset into the object
	 *
	 * @return void
	 * @author Ari Rubinstein
	 **/
	public function load_variables($result){
		foreach ($result as $k=>$v){
			$this->__set($k, $v);
		}
	}
	
	/**
	 * Returns the array parameter for this object
	 *
	 * @return array the parameters for this object
	 * @author Ari Rubinstein
	 **/
	public function get_parameters(){
		return $this->params;
	}
	
	/**
	 * Returns a JSON encoded array of parameters
	 *
	 * @return string the json encoded array for this object
	 * @author Ari Rubinstein
	 **/
	public function toJSON(){
		return json_encode($this->params);
	}
	
	
	/**
	 * Saves or inserts the object into the database based on if it has an id or not
	 *
	 * @return boolean true if the save was successful, false otherwise
	 * @author Ari Rubinstein
	 **/
	public function save(){
		if (method_exists($this, "pre_save")){
			$this->pre_save();
		}
		if (!$this->validate()){
			return false;
		}
		$db = DBClass::start();
		if (isset($this->id)){
			//update
			return $db->update_array($this->tablename(), $this->params, $db->arg("id", $this->id, "="));
		} else {
			//insert
			$id = $db->insert_array($this->tablename(), $this->params);
			if (!$id){
				return false;
			} else {
				if (is_int($id)){
					$this->id = $id;
				}
				return true;
			}
		}
	}
	
	/**
	 * Loads object with data from corresponding id
	 *
	 * @return boolean true if successful, false otherwise
	 * @param int $id the id to load from the database
	 * @author Ari Rubinstein
	 **/
	private function load($id){
		$id = (int)$id;
		$db = DBClass::start();
		$s = "SELECT * FROM ".$this->tablename()." WHERE ".$db->arg("id", (int)$id, "=");
		if (array_key_exists("active", $this->allowed_parameters())){
			$s .= " AND active = 1";
		}
		$result = $db->select_return_row($s);
		if (!$result) return false;
		$this->load_variables($result);
		return true;
	}
	
	
	/**
	 * Finds one object
	 *
	 * @return object the returned object
	 * @author Ari Rubinstein
	 **/
	public function findOne($paramater_array=array()){
		return $this->find($paramater_array,null, true);
	}
	/**
	 * Finds an object based on the parameters given
	 * If no parameters are given, will retrieve all objects from db
	 * @return Object the object that was found
	 * @author Ari Rubinstein
	 **/
	public function find($paramater_array=array(), $orderby = null, $limitToOne = false){
		$db = DBClass::start();
		$searcharray = array();
		if (count($paramater_array) > 0){
			foreach ($paramater_array as $k=>$v){
				if ($this->is_parameter_allowed(strtolower($k))){
					$searcharray[strtolower($k)] = $v;
				}
			}
		}
		if (count($searcharray) == 0){
			//can't search for anything
			$sql = "SELECT * FROM ".$this->tablename();
		} else {
			$sql = "SELECT * FROM ".$this->tablename()." WHERE ";
			$argumentarr = array();
			foreach ($searcharray as $k=>$v){
				$argumentarr[] = $db->arg($k, $v);
			}
			$sql .= implode(" AND ", $argumentarr);
			if ($this->is_parameter_allowed("active")){
				$sql .= " AND active = 1";
			}
			if ($limitToOne){
				$sql .= " LIMIT 1";
			}
		}
		if ($orderby != null){
			$sql .= " ORDER BY " . $db->s($orderby);
		}

		$result = $this->findSQL($sql);
		
		if ($result === false || count($result) == 0){
			return false;
		} else {
			if ($limitToOne){
				return $result[0];
			} else {
				return $result;
			}
		}
		return false;

	}
	public function findSQL($sql){
		$db = DBClass::start();
		$objarr = array();
		$result = $db->select_return_rows($sql);
		if ($result === false || count($result) == 0){
			return false;
		} else {
			foreach ($result as $r){
				eval("\$t = new ".get_class($this)."();");
				$t->load_variables($r);
				$objarr[] = $t;
			}

			return $objarr;
			
		}
		return false;

	}

	/**
	 * Returns a string representing this class
	 *
	 * @return String details about this class and currently set parameters
	 * @author Ari Rubinstein
	 **/
	public function __toString(){
		$outstr = $this->tablename() . " object:".(COMMAND_LINE ? "\n" : "<br>");
		foreach ($this->params as $k=>$v){
			$outstr .= "\t$k: $v" . (COMMAND_LINE ? "\n" : "<br>");
		}		
		return $outstr;
	}
	

	/**
	 * Returns true if the current user in session is the owner.
	 * Returns false if there is no user logged in
	 * @return boolean true if user owns object
	 * @author Ari Rubinstein
	 **/
	public function isOwner(){
		if (!$this->is_parameter_allowed("owner_id"))
			return false;
		if (User::current_user_id() == $this->owner_id)
			return true;
		return false;
	}

	/**
	 * Returns true if the parameter is allowed to be stored in the class
	 *
	 * @return boolean true if the requested parameter can be set
	 * @param string $name the name of the parameter to check
	 * @author Ari Rubinstein
	 **/
	public function is_parameter_allowed($name){
		return in_array($name, $this->allowed_parameters());
	}
	
	/**
	 * Returns true if the property requested is set
	 *
	 * @return boolean true if the requested property is set for this object
	 * @param string $name the name of the parameter to check
	 * @author Ari Rubinstein
	 **/
	public function __isset($name){
		return array_key_exists($name, $this->params);
	}
	
	/**
	 * Unsets requested property if it is set
	 *
	 * @return void
	 * @param string $name the name of the parameter to unset
	 * @author Ari Rubinstein
	 **/
	public function __unset($name){
		unset($this->params[$name]);
	}
	

	/**
	 * Returns the requested parameter
	 *
	 * @return Object the data of the requested parameter if it is set
	 * @param string $name the name of the parameter to get
	 * @author Ari Rubinstein
	 **/
	public function __get($name){		
		if (isset($this->{$name}))
			return $this->params[$name];
		else
			return null;
	}
	
	/**
	 * Sets the requested parameter with the specified value
	 *
	 * @return Object the data of the requested parameter if it is set
	 * @param string $name the name of the parameter to set
	 * @param string $value the value to set the parameter to
	 * @author Ari Rubinstein
	 **/
	public function __set($name, $value){
		if ($this->is_parameter_allowed($name)){
			if (is_object($value)){
				//if value is an object, attempt to get the id from it
				$this->params[$name] = $value->id;
			} else {
				//value is not an object, set it to the actual value
				$this->params[$name] = $value;
			}
		} else {
			throw new Exception("The parameter $name is not allowed for this object");
		}
	}

	/**
	 * Returns the list of parameters which are set
	 *
	 * @return array list of parameters which are set
	 * @author Ari Rubinstein
	 **/
	public function set_parameters(){
		return array_keys($this->params);
	}
	
	/**
	 * Deletes object unless active is an allowed parameter
	 *
	 * @return boolean true if deletion/deactivation was successful
	 * @author Ari Rubinstein
	 **/
	function delete(){
		if (array_key_exists("active", $this->params)){
			$this->active = 0;
			return $this->save();
		} else {
			return $this->force_delete();
		}
	}
	
	/**
	 * Returns whether or not the object is "solid"
	 * My interpretation of "solid" is that it exists in the database
	 *
	 * @return boolean true if solid
	 * @author Ari Rubinstein
	 **/
	function solid(){
		return isset($this->id);
	}
	
	/**
	 * Forces the actual deletion of the object even if it has an active field
	 *
	 * @return boolean true if actual deletion was successful
	 * @author Ari Rubinstein
	 **/
	function force_delete(){
		if (isset($this->id)){
			$db = DBClass::start();
			return ($db->query("DELETE FROM " . $this->tablename() . " WHERE " . $db->arg('id', (int)$this->id) . " LIMIT 1"));
		} else {
			#we don't know who we are deleting!
			return false;
		}
	}
	
	/**
	 * Validates the current class by iterating through the allowed parameters
	 * and checking each one if that parameter is valid
	 *
	 * @return boolean true if class is valid
	 * @author Ari Rubinstein
	 **/
	public function validate(){
		$this->errors = array();
		foreach($this->set_parameters() as $key){
			$v = $this->validate_parameter($key, $this->{$key});
			if (count($v) > 0){
				foreach ($v as $e){
					$this->errors[] = $e;
				}
			}
		}
		foreach($this->required_parameters() as $k){
			if (!array_key_exists($k, $this->params)){
				$this->errors[] = "$k is required";
			}
		}
		
		if (count($this->errors) > 0)
			return false;
		return true;
	}
	
	
}
?>