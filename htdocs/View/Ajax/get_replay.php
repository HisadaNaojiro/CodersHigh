<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Replay.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Favorite.php');

$loader = new Loader;
$User = new User;
$Micropost = new Micropost;
$Replay = new Replay;

$micropostId = !empty($_GET['replay_id'])?  $Replay->getMicropostIdById($_GET['replay_id']) : $_GET['data']['Replay']['micropost_id'];
$MicropostArray = $Micropost->getArrayById($micropostId);
	$uesrName = $User->getNameById($MicropostArray['user_id']);
	$array['Micropost'] = 
		getDataAttribution('micropost',$MicropostArray['id'] ) . 
		formatContentSpace($uesrName , $MicropostArray['content']) .
		formatButtonSpace('micropost',$MicropostArray['id'],$MicropostArray['user_id'],$uesrName) . '</div>';

if(!$ReplayCollection = $Replay->getCollectionByMicropostId($micropostId)){
	$loader->Response->setResponseCode('500');
	$array['Replay'] = 'nothing';
	
}else{
	

	foreach($ReplayCollection as $ReplayArray){
		$userName = $User->getNameById($ReplayArray['other_user_id']);
		$array['Replay'][] = 
			getDataAttribution('replay',$ReplayArray['id'] ) . 
			formatContentSpace($userName , $ReplayArray['content']) .
			formatButtonSpace('replay',$ReplayArray['id'],$ReplayArray['other_user_id'],$uesrName) . '</div>';
	}
}

$loader->Response->setResponseCode('200');
echo json_encode($array);

function getDataAttribution($type,$id)
{
	return "<div data-{$type}-id='{$id}'>";
}

function formatContentSpace($name , $content)
{
	return "
				<div class='micropost-user-info'>
					<p>{$name}</p>
				</div>
				<div class='micropost-content'>
					<p>{$content}</p>
				</div>
			";
}

function formatButtonSpace($type,$id,$userId,$userName)
{
	$Favorite = new Favorite;
	if($type == 'micropost'){
		$checkFavorite = $Favorite->isFavoriteByMicropostIdAndUserId($id,$userId);
		$favoriteCount = $Favorite->getCountByMicropostId($id);
	}else{
		$checkFavorite = $Favorite->isFavoriteByReplayIdAndUserId($id,$userId);
		$favoriteCount = $Favorite->getCountByReplayId($id);
	}

	$favorite = ($checkFavorite)? 'style ="display: none;"' : '' ;
	$unfavorite = (!$checkFavorite)? 'style ="display: none;"' : '' ;

	return "
	<div class='micropost-content-button'>
		<div class='micropost-replay-space'>
			<button  data-toggle='tooltip' title='返信' class='micropost-replay-button' data-recipient='{$userName}'><i class='fa fa-reply' aria-hidden='true'></i></button>
		</div>
		<!-- <div class='micropost-retweet-space'>
			<button data-toggle='tooltip' title='リツイート'  class='micropost-retweet-button'><i class='fa fa-exchange' aria-hidden='true'></i></button>
		</div> -->
		<div class='micropost-favorite-space'>
			<button data-toggle='tooltip' data-target='#replayModal' data-type='add' title='いいね' class='micropost-favorite-button' 
				{$favorite}
			>
				<i class='fa fa-heart' aria-hidden='true'></i>
			</button>
			<button data-toggle='tooltip' data-type='delete' title='いいね取り消し' class='delete-micropost-favorite-button' 
				{$unfavorite}
			>
				<i class='fa fa-heart' aria-hidden='true'></i>
			</button>
			<span class='favorite-count'>{$favoriteCount}</span>
		</div>
	</div>";
}

