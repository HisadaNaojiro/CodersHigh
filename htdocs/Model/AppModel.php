<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/database.php');

class AppModel extends Database
{


	public function fetch($sql,$params = array())
	{

		return $this->execute($sql,(array)$params)->fetch(PDO::FETCH_ASSOC);
	}


	public function fetchAll($sql,$params = array())
	{
	
		return  $this->execute($sql,(array)$params)->fetchAll(PDO::FETCH_ASSOC);
	}
}