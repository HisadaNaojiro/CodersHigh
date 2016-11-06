<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Replay.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');

$loader = new Loader;
$User = new User;
$Replay = new Replay;

$formData = $_POST;
$formData['data']['Replay']['user_id'] = $loader->Session->get('user_id');
$Replay->set($formData['data']);



if(!$id = $Replay->save()){
	$loader->Response->setResponseCode('500');
	return false;
}

$array= ['status' => 'ok'];

$loader->Response->setResponseCode('200');
echo json_encode($array);

