<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Replay.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Notice.php');


$loader = new Loader;
$User = new User;
$Replay = new Replay;
$Notice = new Notice;
$currentUserId = $loader->Session->get('user_id');
$formData = $_POST;
$formData['data']['Replay']['other_user_id'] = $currentUserId;

$userId = $formData['data']['Replay']['user_id'];

$Replay->set($formData['data']);

$notice['Notice'] = [
	'title' =>$User->getNameById($currentUserId),
	'content' => $formData['data']['Replay']['content'],
	'type' => 'replay',
	'micropost_id' => $formData['data']['Replay']['micropost_id'],
	'user_id' => $userId,
	'other_user_id' => $currentUserId,
];


if(!$id = $Replay->save()){
	$loader->Response->setResponseCode('500');
	return false;
}

$notice['Notice']['replay_id'] = $id;

$Notice->set($notice);
$Notice->saveReplay();


$array= ['status' => 'ok'];

$loader->Response->setResponseCode('200');
echo json_encode($array);

