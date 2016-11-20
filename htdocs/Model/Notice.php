<?php

require_once('AppModel.php');

class Notice extends AppModel
{
	private $__params = null;

	public function hasContent($content)
	{
		$sql = 'SELECT * FROM notice WHERE content = ?';
		$array = [$content];
		return $this->fetch($sql,$array);
	}

	public function getCount($id)
	{
		$sql = "SELECT COUNT(*) as notice_count FROM notice WHERE user_id = ? AND valid = 1";
		$array = [$id];
		$NoticeArray = $this->fetch($sql,$array);

		return $NoticeArray['notice_count'];
	}

	public function getCollectionByUserIdForPaginate($userId,$page)
	{
		$offset = ($page - 1) * 10;

		$sql = "SELECT * FROM notice WHERE user_id = ? AND valid = 1 ORDER BY created DESC LIMIT {$offset} , 10";

		$array = [$userId];

		return $this->fetchAll($sql,$array);
	}

	
	public function set($params)
	{
		$this->__params = $params;
	}

	public function deleteFavorite()
	{
		$params = $this->__params;
		$sql = "DELETE FROM notice WHERE user_id = ? AND other_user_id = ? AND micropost_id = ? AND replay_id IS NULL";
		$array = [
			$params['Notice']['user_id'],$params['Notice']['other_user_id'],$params['Notice']['micropost_id']
		];

		return $this->__delete($sql,$array);
	}

	public function deleteFollow()
	{
		$params = $this->__params;
		$sql = "DELETE FROM notice WHERE user_id = ? AND other_user_id = ? AND micropost_id IS NULL AND replay_id IS NULL";
		$array = [
			$params['Notice']['user_id'],$params['Notice']['other_user_id']
		];

		return $this->__delete($sql,$array);
	}

	public function saveFollow()
	{
		$params = $this->__params;
		$sql = "INSERT INTO notice(title,content,type,user_id,other_user_id,created,modified) VALUES (?,?,?,?,?,?,?)";
		$array = [
			$params['Notice']['title'],$params['Notice']['content'],$params['Notice']['type'],
			$params['Notice']['user_id'],$params['Notice']['other_user_id'],
			$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->__save($sql,$array);
	}

	public function saveFavorite()
	{
		$sql = "INSERT INTO notice(title,content,type,user_id,other_user_id,micropost_id,created,modified) VALUES (?,?,?,?,?,?,?,?)";
		$params = $this->__params;

		$array = [
			$params['Notice']['title'],$params['Notice']['content'],$params['Notice']['type'],
			$params['Notice']['user_id'],$params['Notice']['other_user_id'],
			$params['Notice']['micropost_id'],$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->__save($sql,$array);
	}

	public function saveReplay()
	{
		$params = $this->__params;
		$sql = "INSERT INTO notice(title,content,type,user_id,other_user_id,micropost_id,replay_id,created,modified) VALUES (?,?,?,?,?,?,?,?,?)";
		$array = [
			$params['Notice']['title'],$params['Notice']['content'],$params['Notice']['type'],
			$params['Notice']['user_id'],$params['Notice']['other_user_id'],$params['Notice']['micropost_id'],
			$params['Notice']['replay_id'],$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->__save($sql,$array);
	}

	private function __save($sql,$array)
	{


		return $this->insert($sql,$array);
	}

	private function __delete($sql,$array)
	{
		return $this->execute($sql,$array);
	}

}