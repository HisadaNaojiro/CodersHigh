<?php

require_once('Validate.php');

class UserValidate extends Validate
{
	public $__params = null;
	private $__errors = [];

	public function __construct($value)
	{
		$this->__params = $value;
	}
	public function validate()
	{

		$this->checkPresence();

		if(
			!empty($this->__params['User']['email']) && 
			!empty($this->__params['User']['emailConfirmation'])
		){
			$this->__errors['errors']['User']['email'] = 
				($this->confirmation($this->__params['User']['email'],$this->__params['User']['emailConfirmation']))? '' : 'メールアドレスが一致しません';
		}

		if(
			!empty($this->__params['User']['password']) && 
			!empty($this->__params['User']['passwordConfirmation'])
		){
			$this->__errors['errors']['User']['password'] = 
				($this->confirmation($this->__params['User']['password'],$this->__params['User']['passwordConfirmation']))? '' : 'パスワードが一致しません';
		}

		$result = array_filter($this->__errors);
		if(empty($result)){
			return $this->__errors;
		}

		return true;

	}

	private function checkPresence()
	{
		$value = $this->__params;

		$result = [];

		$result['User']['name']	= 
			($this->presence($value['User']['name']))? '' : 'ニックネームを入力してください';
		$result['User']['email'] = 
			($this->presence($value['User']['email']))? '' : 'メールアドレスを入力してください'; 
		$result['User']['emailConfirmation'] = 
			($this->presence($value['User']['emailConfirmation']))? '' : '確認用メールアドレスを入力してください'; 
		$result['User']['password']	= 
			($this->presence($value['User']['password']))? '' : 'パスワードを入力してください';
		$result['User']['passwordConfirmation']	= 
			($this->presence($value['User']['passwordConfirmation'])) ? '' : '確認用パスワードを入力してください';


		if(!empty($result)){
			$this->__errors['errors'] = $result;
			return false;
		}
			return true;
	}

	private function confirmation($value,$confirmation)
	{
		return ($value === $confirmation)? true : false;
	}
}