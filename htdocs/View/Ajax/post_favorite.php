<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Favorite.php');



$loader = new Loader;
$Favorite = new Favorite;

$formData = $_POST;
$formData['data']['Favorite']['user_id'] = $loader->Session->get('user_id');
$Favorite->set($formData['data']);

if($formData['type'] ===  'add'){
	if(!$id = $Favorite->save()){
		$loader->Response->setResponseCode('500');
		return false;
	}
}else{

	if(!$Favorite->delete()){
		$loader->Response->setResponseCode('500');
		return false;
	}

}

$favoriteCount = $Favorite->getCountByMicropostId($formData['data']['Favorite']['micropost_id']);

echo json_encode($favoriteCount);
