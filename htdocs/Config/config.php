<?php

require_once('loader.php');

class Config
{

	public function setInstance($class,$value = null)
	{
		if(!empty($value)){
			return new $class($value);
		}
		return new $class;
	}
}
