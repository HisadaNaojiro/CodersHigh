<?php

require_once('AppModel.php');
require_once('Validate/User.php');

class User extends AppModel
{
	private $__params = null;
	private $Validate = null;
	public $ValidationErrors = null;
	
	public function set($params)
	{
		$this->__params = $params;
		$this->Validate = new UserValidate($this->__params);
	}

	public function validates()
	{
		$result = $this->Validate->validate($this->__params);

		if($result !== true){
			$this->ValidationErrors = $result['errors'];
			return false;
		}
		return true;
	}

	public function save($params)
	{
		$sql = "INSERT INTO users(name,email,password,created,modified) VALUES (?,?,?,?,?)";

		$array = [
			$params['User']['name'],$params['User']['email'],
			password_hash($params['User']['password'],PASSWORD_DEFAULT),
			$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->insert($sql,$array);
	}
}