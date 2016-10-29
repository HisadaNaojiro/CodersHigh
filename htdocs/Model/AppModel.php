<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/database.php');

class AppModel extends Database
{


	public function fetch($sql,$params = array())
	{

		return $this->execute($sql,$params)->fetch(PDO::FETCH_ASSOC);
	}


	public function fetchAll($sql,$params = array())
	{
	
		return  $this->execute($sql,$params)->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insert($sql,$params = array())
	{

		$this->startTransaction();
		try{

			$this->execute($sql,$params);
			$id = $this->lastInsertId();

		}catch(PDOException $e){

			echo $e->getMessage();
			exit;
		}

		$this->commit();
		return $id;
	}


	public function setCurrentDate()
	{
		return date('Y-m-d H:i:s');
	}
}