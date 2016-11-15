<?php

require_once('AppModel.php');
require_once('Validate/User.php');

class User extends AppModel
{
	private $__params = null;
	private $Validate = null;
	public $ValidationErrors = null;

	public function getNameById($id)
	{
		$sql = "SELECT name FROM user WHERE id = ?";
		$array = [$id];
		$UserArray = $this->fetch($sql,$array);

		return $UserArray['name'];
	}

	public function getArrayByName($name)
	{
		$sql = "SELECT * FROM user WHERE name = ?";
		$array = [$name];
		return  $this->fetch($sql,$array);
	}
	
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

	public function getArrayById($id)
	{
		$sql = "SELECT * FROM user WHERE id = ?";
		$array = [$id];
		return $this->fetch($sql,$array);
	}

	public function save()
	{
		$sql = "INSERT INTO user(name,email,password,created,modified) VALUES (?,?,?,?,?)";
		$params = $this__params;
		$array = [
			$params['User']['name'],$params['User']['email'],
			password_hash($params['User']['password'],PASSWORD_DEFAULT),
			$this->setCurrentDate(),$this->setCurrentDate()
		];

		return $this->insert($sql,$array);
	}

	public function update()
	{
		
		$params = $this->__params;
		if(isset($params['User']['password'])){ 
			$sql ="UPDATE user SET name = ?, email = ? ,password = ? ,modified = ? WHERE id = ?";
			$array = [
				$params['User']['name'],$params['User']['email'],
				password_hash($params['User']['password'],PASSWORD_DEFAULT),
				$this->setCurrentDate(),$params['User']['id']
			];
		}else{
			$sql ="UPDATE user SET name = ? ,email = ?,modified = ? WHERE id = ?";
			$array = [
				$params['User']['name'],$params['User']['email'],
				$this->setCurrentDate(),$params['User']['id']
			];
		}

		return $this->execute($sql,$array);
	}

	public function authenticate()
	{
		$params = $this->__params;
		$sql = "SELECT * FROM user WHERE email = ?";
		$array = [$params['User']['email']];
		$UserArray = $this->fetch($sql,$array);

		if(!$UserArray && password_verify($params['User']['password'], $UserArray['password'])){
			$this->Validate = ['errors' => ['conbination' => 'メールアドレスかパスワードが間違っています']];
			return $this->Validate;
		}

		return $UserArray;
	}
}