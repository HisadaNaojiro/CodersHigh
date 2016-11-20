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


	public function isFavoriteByMicropostIdAndUserId($micropostId,$userId)
	{	
		$sql = "SELECT * FROM favorite WHERE micropost_id = ? AND user_id = ? AND valid = 1";
		$array = [$micropostId,$userId];
		$FavoriteArray = $this->fetch($sql,$array);
		return (!empty($FavoriteArray))? true : false;
	}

	public function isFavoriteByReplayIdAndUserId($replayId,$userId)
	{	
		$sql = "SELECT * FROM favorite WHERE replay_id = ? AND user_id = ? AND valid = 1";
		$array = [$replayId,$userId];
		$FavoriteArray = $this->fetch($sql,$array);
		return (!empty($FavoriteArray))? true : false;
	}

	public function getCountByMicropostId($id)
	{
		$sql = "SELECT COUNT(*) as favorite_count FROM favorite WHERE micropost_id = ? AND valid = 1";
		$array = [$id];
		$FavoriteCount = $this->fetch($sql,$array);
		return ($FavoriteCount['favorite_count'] > 0) ? $FavoriteCount['favorite_count'] : '' ;
	}

	public function getCountByReplayId($id)
	{
		$sql = "SELECT COUNT(*) as favorite_count FROM favorite WHERE replay_id = ? AND valid = 1";
		$array = [$id];
		$FavoriteCount = $this->fetch($sql,$array);
		return ($FavoriteCount['favorite_count'] > 0) ? $FavoriteCount['favorite_count'] : '' ;
	}

	public function save()
	{
		$params = $this->__params;

		if(!empty($params['Favorite']['micropost_id'])){
			$sql = "INSERT INTO favorite(micropost_id,user_id,other_user_id,created,modified) VALUES (?,?,?,?,?)";

			$array = [
				$params['Favorite']['micropost_id'],$params['Favorite']['user_id'],$params['Favorite']['other_user_id'],
				$this->setCurrentDate(),$this->setCurrentDate()
			];
		}else{
			$sql = "INSERT INTO favorite(replay_id,user_id,other_user_id,created,modified) VALUES (?,?,?,?,?)";

			$array = [
				$params['Favorite']['replay_id'],$params['Favorite']['user_id'],$params['Favorite']['other_user_id'],
				$this->setCurrentDate(),$this->setCurrentDate()
			];
		}
		

		return $this->insert($sql,$array);
	}

	public function delete()
	{
		$params = $this->__params;
		if(!empty($params['Favorite']['micropost_id'])){

			$sql = "DELETE FROM favorite WHERE micropost_id = ?  AND user_id = ?";
			$array = [
				$params['Favorite']['micropost_id'],$params['Favorite']['user_id']
			];
		}else{
			$sql = "DELETE FROM favorite WHERE replay_id = ?  AND user_id = ?";
			$array = [
				$params['Favorite']['replay_id'],$params['Favorite']['user_id']
			];
		}
		
		return $this->execute($sql,$array);

	}
}