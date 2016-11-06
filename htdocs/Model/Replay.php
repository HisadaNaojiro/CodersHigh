<?php

require_once('AppModel.php');

class Replay extends AppModel
{
	private $__params = null;

	public function getCollectionByMicropostId($micropostId)
	{
		$sql = "SELECT * FROM replay WHERE micropost_id = ?";
		$array = [$micropostId];

		return $this->fetchAll($sql,$array);
	}
	
	public function set($params)
	{
		$this->__params = $params;
	}

	public function save()
	{
		$sql = "INSERT INTO replay(content,user_id,micropost_id,created,modified) VALUES (?,?,?,?,?)";
		$params = $this->__params;

		$array = [
			$params['Replay']['content'],$params['Replay']['user_id'] ,$params['Replay']['micropost_id'],
			$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->insert($sql,$array);
	}

}