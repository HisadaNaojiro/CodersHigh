<?php

require_once('AppModel.php');

class Follow extends AppModel
{
	private $__params = null;
	
	public function set($params)
	{
		$this->__params = $params;
	}

	public function isFollow($userId,$otherUserId)
	{
		$sql = "SELECT * FROM follow WHERE follow_id = ? AND followed_id = ? AND valid = 1";

		$array = [$userId,$otherUserId];

		return $this->fetch($sql,$array);
	}

	public function save()
	{
		$sql = "INSERT INTO follow(follow_id,followed_id,created,modified) VALUES (?,?,?,?)";
		$params = $this->__params;

		$array = [
			$params['Follow']['follow_id'],$params['Follow']['followed_id'],
			$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->insert($sql,$array);
	}

	public function delete()
	{
		$params = $this->__params;
		$sql = "DELETE FROM follow WHERE follow_id = ? AND followed_id = ?";
		$array = [$params['Follow']['follow_id'],$params['Follow']['followed_id']];
		return $this->execute($sql,$array);
	}

}
