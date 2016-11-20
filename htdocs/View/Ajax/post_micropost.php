<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');

$loader = new Loader;
$User = new User;
$Micropost = new Micropost;

$formData = $_POST;
$formData['data']['Micropost']['user_id'] = $loader->Session->get('user_id');
$UserArray = $User->getArrayById($loader->Session->get('user_id'));
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
'<div class="each-micropost-space" data-micropost-id="' . $MicropostArray['id'] . '">
	<div class="micropost-user-info">
		<p><span>画像</span>'. $UserArray['name'] .'</p>
	</div>
	<div class="micropost-content">
		<p>' . $MicropostArray['content'].'</p>
	</div>
	<div class="micropost-content-button">
		<div class="micropost-replay-space">
			<button  data-toggle="tooltip" title="返信" class="micropost-replay-button"><i class="fa fa-reply" aria-hidden="true"></i></button>
		</div>
		<div class="micropost-retweet-space">
			<button data-toggle="tooltip" title="リツイート"  class="micropost-retweet-button"><i class="fa fa-exchange" aria-hidden="true"></i></button>
		</div>
		<div class="micropost-favorite-space">
			<button data-toggle="tooltip" title="いいね" class="micropost-favorite-button"><i class="fa fa-heart" aria-hidden="true"></i></button>
		</div>
	</div>
</div>'];


$loader->Response->setResponseCode('200');
echo json_encode($array);

