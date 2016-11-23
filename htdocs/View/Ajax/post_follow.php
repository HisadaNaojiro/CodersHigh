<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Follow.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Notice.php');

$loader = new Loader;
$Follow = new Follow;
$User = new User;
$Notice = new Notice;
$currentUerId = $loader->Session->get('user_id');
$formData = $_POST;
$formData['data']['Follow']['follow_id'] = $currentUerId;
$Follow->set($formData['data']);
$userName = $User->getNameById($formData['data']['Follow']['follow_id']);
$notice['Notice'] = [
		'title' => '<a href="' . $loader->SiteSetting->getUrl() . '/User/detail.php?name=' . $userName. '">' . $userName . '</a>があなたをフォローしました',
		'content' => '',
		'user_id' => $formData['data']['Follow']['followed_id'],
		'other_user_id' => $currentUerId,
		'type' => 'follw'
];

	$Notice->set($notice);

if($formData['type'] === 'follow'){
	if(!$id = $Follow->save()){
		$loader->Response->setResponseCode('500');
		return false;
	}
	$Notice->saveFollow();
}else{
	if(!$id = $Follow->delete()){
		$loader->Response->setResponseCode('500');
		return false;
	}
	$Notice->deleteFollow();
}





$array= ['status' => 'ok'];

$loader->Response->setResponseCode('200');
echo json_encode($array);
