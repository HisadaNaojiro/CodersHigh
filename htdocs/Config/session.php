<?php

require_once('loader.php');

class Session
{
	protected static $sessionStarted = false;
	protected static $sessionIdRegenerated = false;

	public function __construct()
	{
		if(!self::$sessionStarted){
			session_start();

			self::$sessionStarted = true;
		}
	}

	public function set($name,$value)
	{
		$_SESSION[$name] = $value;
	}
	
	public function get($name , $default = null)
	{
		if(isset($_SESSION[$name])){
			return $_SESSION[$name];
		}

		return $default;
	}

	public function remove($name)
	{
		unset($_SESSION[$name]);
	}

	public function clear()
	{
		$_SESSION = [];
	}

	public function regenerate($destroy = null)
	{
		if(self::$sessionIdRegenerated){
			session_regenerate_id($destroy);

			self::$sessionIdRegenerated = true;
		}
	}
}
