<?php

class Request
{
	public function isPost()
	{
		return ($_SERVER['REQUEST_METHOD'] == 'POST')? true : false; 
	}

	public function isGet()
	{
		return ($_SERVER['REQUEST_METHOD'] == 'GET')? true : false;
	}

	public function redirect($path)
	{
		$url = 'http://' .  $_SERVER['HTTP_HOST'] .'/' . 'View'. '/' . $path . '.'. 'php';
		header("Location:". $url);
		exit;
	}
}