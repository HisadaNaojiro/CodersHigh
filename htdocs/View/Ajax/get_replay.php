<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Replay.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');

$loader = new Loader;
$User = new User;
$Replay = new Replay;

$array= [];

if(!$ReplayCollection = $Replay->getCollectionByMicropostId($_GET['data']['Replay']['micropost_id'])){
	$loader->Response->setResponseCode('500');
	$array = ['nothing'];
	
}else{



	foreach($ReplayCollection as $ReplayArray){

	$text = 
		'<div  data-replay-id="' . $ReplayArray['id'] . '">
			<div class="micropost-user-info">
				<p><span>画像</span>'. $User->getNameById($ReplayArray['user_id']) .'</p>
			</div>
			<div class="micropost-content">
				<p>' . $ReplayArray['content'].'</p>
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
		</div>';
		$array[] = $text;
	}
}

$loader->Response->setResponseCode('200');
echo json_encode($array);

