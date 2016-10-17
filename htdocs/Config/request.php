<?php

class Request
{
	public function isPost()
	{
		return !empty($_POST)? true : false; 
	}

	public function isGet()
	{
		return !empty($_GET)? true : false;
	}
}