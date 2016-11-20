<?php

require_once('AppModel.php');

class Micropost extends AppModel
{
	private $__params = null;
	
	public function set($params)
	{
		$this->__params = $params;
	}

	public function getContentById($id)
	{
		$sql = "SELECT content FROM micropost WHERE id = ? AND valid = 1";
		$array = [$id];

		$MicropostAaray = $this->fetch($sql,$array);
		return $MicropostAaray['content'];
	}

	public function getArrayById($id)
	{
		$sql = "SELECT * FROM micropost WHERE id = ? AND valid = 1";
		$array = [$id];
		return $this->fetch($sql,$array);
	}

	public function getCollectionForPaginate($userId,$page)
	{
		$offset = ($page - 1) * 10;

		$sql = "SELECT m.id as m_id ,m.content as m_content, m.created as m_created , m.modified as m_modified ,m.user_id as m_user_id,
				u.id as u_id,u.name as u_name, u.email as u_email,u.created as u_created , u.modified as u_modified,
				f.id as f_id , f.follow_id as f_follow_id , f.followed_id as f_followed_id , f.created as f_created , f.modified as f_modified 
				FROM micropost as m INNER JOIN user as u  ON m.user_id = u.id
				INNER JOIN follow as f ON u.id = f.follow_id
				WHERE u.id = ? OR u.id IN (SELECT followed_id FROM follow WHERE follow_id = ?) AND m.valid = 1
				ORDER BY m.created DESC LIMIT {$offset} , 10";

		$array = [$userId,$userId];
		return $this->fetchAll($sql,$array);
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