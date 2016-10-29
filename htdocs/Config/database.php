<?php

class Database
{
	public $default = null;
	public $pdo = null;

	public $development = [
		'host'		=> 'localhost',
		'database'	=> 'mydb',
		'username'	=>	'root',
		'password'	=>	'root',
		'charset'	=>	'utf8'
	];

	public function __construct()
	{
		$this->setEnvironment();
		$this->pdo = $this->setConnection();
	}

	public function setConnection()
	{
		try{
			$pdo = new PDO ("mysql:host={$this->default['host']};dbname={$this->default['database']}","{$this->default['username']}" , "{$this->default['password']}");
			
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$pdo->exec("SET NAMES {$this->default['charset']}");
			
			return $pdo;
			 // $stmt = $pdo->query('select * from sample');
			 // $sample = $stmt->fetch();
			//$stmt->fetchAll(PDO::FETCH_ASSOC);
			//$stmt = $pdo->prepare("select * from *  where id = ? and name = ?");
			//$stmt->execute(array($id , $name));
		}catch(PDOException $e){
			echo $e->getMessage();
			exit;
		}
	}

	public function execute ($sql,$params = array())
	{
		try{


			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($params);

			return $stmt;

		}catch(PDOException $e){

			echo $e->getMessage();
			exit;
		}

	}

	public function startTransaction()
	{
		return $this->pdo->beginTransaction();
	}

	public function commit()
	{
		return $this->pdo->commit();
	}

	public function rollback()
	{
		return $this->pdo->rollback();
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId('id');
	}

	private function setEnvironment()
	{
		$this->default = $this->development;
	}
}