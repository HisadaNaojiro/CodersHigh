<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');

$loader = new Loader;
$User = new User;
$Micropost = new Micropost;

$formData = $_POST;
$formData['data']['Micropost']['user_id'] = $loader->Session->get('user_id');
$UserArray = $User->getUserArrayById($loader->Session->get('user_id'));
$Micropost->set($formData['data']);



if(!$id = $Micropost->save()){
	$loader->Response->setResponseCode('500');
	return false;
}

if(!$MicropostArray = $Micropost->getArrayById($id)){
	$loader->Response->setResponseCode('500');
	return false;
}


$array=[ 'text' =>  
'<div class="each-micropost-space" id="micropost_id-' . $MicropostArray['id'] . '">
	<div class="micropost-user-info">
		<p><span>画像</span>'. $UserArray['name'] .'</p>
	</div>
	<div class="micropost-content">
		<p>' . $MicropostArray['content'].'</p>
	</div>
	<div class="micropost-content-button">
		<span>いいね</span><span>リツイート</span><span>返信</span>
	</div>
</div>'];


$loader->Response->setResponseCode('200');
echo json_encode($array);

