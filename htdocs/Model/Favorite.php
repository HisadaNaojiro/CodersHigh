<?php

require_once('AppModel.php');

class Favorite extends AppModel
{
	private $__params = null;
	
	public function set($params)
	{
		$this->__params = $params;
	}

	public function getMicropostIdById($id)
	{
		$sql = "SELECT micropost_id FROM favorite WHERE id = ? AND valid = 1";
		$array = [$id];
		$FavoriteArray = $this->fetch($sql,$array);
		return $FavoriteArray['micropost_id'];
	}

	public function isFavoriteByUserId($MicropostId,$userId)
	{	
		$sql = "SELECT * FROM favorite WHERE micropost_id = ? AND user_id = ? AND valid = 1";
		$array = [$MicropostId,$userId];
		$FavoriteArray = $this->fetch($sql,$array);
		return (!empty($FavoriteArray))? true : false;
	}

	public function getCountByMicropostId($id)
	{
		$sql = "SELECT COUNT(*) as favorite_count FROM favorite WHERE micropost_id = ?";
		$array = [$id];
		$FavoriteCount = $this->fetch($sql,$array);
		return ($FavoriteCount['favorite_count'] > 0) ? $FavoriteCount['favorite_count'] : '' ;
	}

	public function save()
	{
		$sql = "INSERT INTO favorite(micropost_id,user_id,created,modified) VALUES (?,?,?,?)";
		$params = $this->__params;

		$array = [
			$params['Favorite']['micropost_id'],$params['Favorite']['user_id'],
			$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->insert($sql,$array);
	}

	public function delete()
	{
		$params = $this->__params;
		$sql = "DELETE FROM favorite WHERE micropost_id = ?  AND user_id = ?";
		$array = [$params['Favorite']['micropost_id'],$params['Favorite']['user_id']];
		return $this->execute($sql,$array);

	}
}