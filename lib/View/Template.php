<?php
/** 
* Partial Class
* @author Ari Rubinstein
* @package QuickEval
*/
class Partial {
	var $t;
    
	/**
	 * Renders the form link using the specified parameters
	 * 
	 * @param string $form_name name of form to be rendered, string $title form title, string $linktext text for the link, int $height height of form, int $width width of the form, boolean $modal can be either modal or modeless
	 * @return string link for the form with all parameters
	 * @author Ari Rubinstein
	 **/
	public static function Render_Form_Link($form_name, $title, $linktext, $height = 25, $width = 250, $modal = "false"){
		return "<a href='/form.php?name=$form_name&KeepThis=true&TB_iframe=true&modal=true\" class=\"thickbox\" title=\"$title\">$linktext</a>";
	}
	public function __toString(){
		return $this->fetch();
	}
    
	/**
	 * Renders the partial based on the given name and array 
	 * 
	 * @param string $partial_name name of the partial you want to render, $var_array the array to be passed into partial 
	 * @author Ari Rubinstein
	 **/
	public static function Render_Partial($partial_name, $var_array = null){
		$temp = new Partial($partial_name, $var_array);
		echo $temp->fetch();
	}
	
	function set($name, $value) {
		$this->t->set($name, $value);
	}

	function Partial($partial_name, $var_array = null){
		if (file_exists(TEMPLATE_DIRECTORY.'partials/'.$partial_name.'.tmpl.php')){
			$this->t = new Template(TEMPLATE_DIRECTORY.'partials/'.$partial_name.'.tmpl.php');
		} else {
			$this->t = new Template(TEMPLATE_DIRECTORY.$partial_name);
		}
		if ($var_array != null){
			foreach ($var_array as $k => $v){
				$this->t->set($k, $v);
			}
		}
	}
	function fetch(){
		return $this->t->fetch();
	}
}

/** 
* Template Class
* @author Ari Rubinstein
* @package QuickEval
*/
class Template {
    
	/**
	 * Wraps the html tag around the object passed in
	 * 
	 * @param object $inobj object to be wrapped, string $tag_to_wrap tag used to wrap around $inobj  
	 * @return string $out the new object wrapped in the html tag
	 * @author Ari Rubinstein
	 **/
	public static function wrap_array($inobj, $tag_to_wrap = "p"){
		//if $inobj is an array, iterate, otherwise, just return the one line
		$out = "";
		if (is_array($inobj)){
			foreach ($inobj as $o){
				$out .= "<$tag_to_wrap>$o</$tag_to_wrap>";
			}
		} else {
			$out .= "<$tag_to_wrap>$inobj</$tag_to_wrap>";
		}
		return $out;
	}

	var $vars; /// Holds all the template variables

	/**
		* Constructor
		*
		* @param $file string the file name you want to load
		*/
	function Template($file = null) {
		$this->file = $file;
	}

	/**
		* Set a template variable.
		*/
	function set($name, $value) {
		if (method_exists($value, "fetch")){
			$this->vars[$name] = $value->fetch();
		} else {
			$this->vars[$name] = $value;
		}
	}

	/**
		* Open, parse, and return the template file.
		*
		* @param $file string the template file name
		*/
	function fetch($file = null) {
		if(!$file) $file = $this->file;

		@extract($this->vars);          // Extract the vars to local namespace
		ob_start();                    // Start output buffering
		include($file);                // Include the file
		$contents = ob_get_contents(); // Get the contents of the buffer
		ob_end_clean();                // End buffering and discard
		return $contents;              // Return the contents
	}
}

/**
	* An extension to Template that provides automatic caching of
	* template contents.
	*/
class CachedTemplate extends Template {
	var $cache_id;
	var $expire;
	var $cached;

	/**
		* Constructor.
		*
		* @param $cache_id string unique cache identifier
		* @param $expire int number of seconds the cache will live
		*/
	function CachedTemplate($cache_id = null, $expire = 900) {
		$this->Template();
		$this->cache_id = $cache_id ? 'cache/' . md5($cache_id) : $cache_id;
		$this->expire   = $expire;
	}

	/**
		* Test to see whether the currently loaded cache_id has a valid
		* corrosponding cache file.
		*/
	function is_cached() {
		if($this->cached) return true;

		// Passed a cache_id?
		if(!$this->cache_id) return false;

		// Cache file exists?
		if(!file_exists($this->cache_id)) return false;

		// Can get the time of the file?
		if(!($mtime = filemtime($this->cache_id))) return false;

		// Cache expired?
		if(($mtime + $this->expire) < time()) {
			@unlink($this->cache_id);
			return false;
		}
		else {
			/**
				* Cache the results of this is_cached() call.  Why?  So
				* we don't have to double the overhead for each template.
				* If we didn't cache, it would be hitting the file system
				* twice as much (file_exists() & filemtime() [twice each]).
				*/
			$this->cached = true;
			return true;
		}
	}

	/**
		* This function returns a cached copy of a template (if it exists),
		* otherwise, it parses it as normal and caches the content.
		*
		* @param $file string the template file
		*/
	function fetch_cache($file) {
		if($this->is_cached()) {
			$fp = @fopen($this->cache_id, 'r');
			$contents = fread($fp, filesize($this->cache_id));
			fclose($fp);
			return $contents;
		}
		else {
			$contents = $this->fetch($file);

			// Write the cache
			if($fp = @fopen($this->cache_id, 'w')) {
				fwrite($fp, $contents);
				fclose($fp);
			}
			else {
				die('Unable to write cache.');
			}

			return $contents;
		}
	}
}
?>