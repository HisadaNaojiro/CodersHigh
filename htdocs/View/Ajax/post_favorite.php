<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Favorite.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Notice.php');

$loader = new Loader;
$Favorite = new Favorite;
$User = new User;
$Micropost = new Micropost;
$Notice = new Notice;

$formData = $_POST;
$currentUserId = $loader->Session->get('user_id');
$micropostId = $formData['data']['Favorite']['micropost_id'];
$formData['data']['Favorite']['other_user_id'] = $currentUserId;
$userName = $User->getNameById($currentUserId);

$notice['Notice'] = [
	'title' =>  '<a href="' . $loader->SiteSetting->getUrl() . '/User/detail.php?name=' . $userName. '">' . $userName . '</a>さんがあなたのツイートをいいねしました',
	'content' =>  $Micropost->getContentById($micropostId),
	'type' => 'favorite',
	'user_id' => $formData['data']['Favorite']['user_id'],
	'other_user_id' => $currentUserId,
	'micropost_id' => $micropostId,

];

$Favorite->set($formData['data']);
$Notice->set($notice);

if($formData['type'] ===  'add'){ 
	if(!$id = $Favorite->save()){
		$loader->Response->setResponseCode('500');
		return false;
	}

	if($formData['data']['Favorite']['user_id'] != $currentUserId){		

		$Notice->saveFavorite();
	}

}else{

	if(!$Favorite->delete()){
		$loader->Response->setResponseCode('500');
		return false;
	}

	$Notice->deleteFavorite();

}

if(!empty($formData['data']['Favorite']['micropost_id'])){
	$favoriteCount = $Favorite->getCountByMicropostId($formData['data']['Favorite']['micropost_id']);
}else{
	$favoriteCount = $Favorite->getCountByReplayId($formData['data']['Favorite']['replay_id']);
}



echo json_encode($favoriteCount);
