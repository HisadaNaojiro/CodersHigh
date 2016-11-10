<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Follow.php');

$loader = new Loader;
$Follow = new Follow;

$formData = $_POST;
$formData['data']['Follow']['follow_id'] = $loader->Session->get('user_id');
$Follow->set($formData['data']);


if($formData['type'] === 'follow'){
	if(!$id = $Follow->save()){
		$loader->Response->setResponseCode('500');
		return false;
	}
}else{
	if(!$id = $Follow->delete()){
		$loader->Response->setResponseCode('500');
		return false;
	}
}


$array= ['status' => 'ok'];

$loader->Response->setResponseCode('200');
echo json_encode($array);
