<?php

use \Grithin\Url;

/** Manage a list of to-be included assets that may also have dependencies  */
Class IncludeList{
	/** @var array $available	array of available items.  Items in format [< path >, < handle >, < dependencies >] */
	public $available = [];
	public $available_paths = [];
	/** add an available asset for inclusion based on dependency
	@param	string	$path	path of asset
	@param	string	$handle	handle/name of asset
	@param	array	dependencies	list of assets this asset also requires
	*/
	public function available_add($path, $handle='', $dependencies=[]){
		$path = Url::resolve_relative($path);
		$add = [$path, $handle, $dependencies];
		if($handle){
			$this->available[$handle] = &$add;
		}
		$this->available_paths[$path] = &$add;
	}

	public $added = [];
	public $added_paths = [];
	public $added_handles = [];

	/** get the currently added asset paths */
	public function paths(){
		return array_map(function($item){ return $item[0];}, $this->added);
	}

	/** determine if a string, which could be either a handle or a path, has been added */
	public function check_added($path_or_handle){
		return $this->check_added_handle($path_or_handle) || $this->check_added_path($path_or_handle);
	}
	/** determine if a handle has been added */
	public function check_added_handle($handle){
		if(isset($this->added_handles[$handle])){
			return true;
		}
		return false;
	}
	/** determine if a path is already added */
	public function check_added_path($path){
		$path = Url::resolve_relative($path);
		if(isset($this->added_paths[$path])){
			return true;
		}
		return false;
	}

	/** add from the available dependencies */
	public function add_from_available($name_or_path){
		if(isset($this->available[$name_or_path])){
			$dep = $this->available[$name_or_path];
		}else{
			$path = Url::resolve_relative($name_or_path);
			if(isset($this->available_paths[$path])){
				$dep = $this->available_paths[$path];
			}
		}
		if(!$dep){
			throw new \Exception('Dependency not found "'.$name_or_path.'"');
		}
		$this->add($dep[0], $dep[1], $dep[2]);
	}

	/** add to the list of assets to be included
	@param	string	$path	path to asset
	@param	string	$handle	handle/name of asset
	@param	array	$dependencies	list of dependencies, or single dependency string
	*/
	public function add($path, $handle=false, $dependencies=[]){
		Url::resolve_relative($path);
		if($this->check_added_path($path)){
			return false;
		}
		foreach($dependencies as $dependency){
			if(!$this->check_added($dependency)){
				$this->add_from_available($dependency);
			}
		}

		$add = [$path, $handle, $dependencies];;
		$this->added[] = &$add;
		$this->added_paths[$add[0]] = &$add;
		if($handle){
			$this->added_handles[$add[1]] = &$add;
		}
	}
}