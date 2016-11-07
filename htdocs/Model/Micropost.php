<?php

require_once('AppModel.php');

class Micropost extends AppModel
{
	private $__params = null;
	
	public function set($params)
	{
		$this->__params = $params;
	}

	public function getArrayById($id)
	{
		$sql = "SELECT * FROM micropost WHERE id = ? AND valid = 1";
		$array = [$id];
		return $this->fetch($sql,$array);
	}

	public function getCollectionForPaginate($page)
	{
		$offset = ($page - 1) * 10;

		$sql = "SELECT * FROM micropost WHERE valid = 1 ORDER BY created DESC LIMIT {$offset} , 10";
		return $this->fetchAll($sql);
	}

	public function getCollectionByUserIdForPaginate($userId,$page)
	{
		$offset = ($page - 1) * 10;

		$sql = "SELECT * FROM micropost WHERE user_id = ? AND valid = 1 ORDER BY created DESC LIMIT {$offset} , 10";

		$array = [$userId];

		return $this->fetchAll($sql,$array);
	}

	public function getCount()
	{
		$sql = "SELECT COUNT(*) as micropost_count FROM micropost WHERE valid = 1";
		$count =  $this->fetch($sql);
		return $count['micropost_count'];
	}

	public function getCollection()
	{
		$sql = "SELECT * FROM micropost WHERE valid = 1 ORDER BY created DESC";
		return $this->fetchAll($sql);
	}

	public function save()
	{
		$sql = "INSERT INTO micropost(content,created,modified,user_id) VALUES (?,?,?,?)";
		$params = $this->__params;

		$array = [
			$params['Micropost']['content'],$this->setCurrentDate(),$this->setCurrentDate(),
			$params['Micropost']['user_id']
		];

		return $this->insert($sql,$array);
	}
}