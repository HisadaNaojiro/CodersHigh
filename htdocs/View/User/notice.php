<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Replay.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Favorite.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Follow.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Notice.php');



	$metaData = [
		'title' => 'Home'
	];
	$loader = new Loader;
	$User = new User;
	$Notice = new Notice;
	$Micropost = new Replay;
	$Follow = new Follow;
	$Favorite = new Favorite;
	$loader->SiteSetting->setMetaData($metaData);
	$message =!empty($loader->Session->get('message'))?$loader->Session->get('message') : '';
	$loader->Session->remove('message');
	$UserArray = $User->getArrayById($loader->Session->get('user_id'));
	$currentUserId  = $loader->Session->get('user_id');
	$maxPages = ceil($Notice->getCount($UserArray['id'])  / 10);
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$nextPage = $page + 1;
	$NoticeCollection = $Notice->getCollectionByUserIdForPaginate($UserArray['id'],$page);
	
	include_once($loader->View->getLayout('header'));

	
?>
<body>

<div id="js-micropost-provider" 
	data-micropost-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_micropost.php';?>" 
	data-favorite-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_favorite.php';?>"
	data-replay-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_replay.php';?>"
	data-replay-get-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/get_replay.php';?>"
	data-follow-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_follow.php';?>"
	data-micropost-detail-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/User/detail.php';?>"
></div>
<?php 
	echo $loader->View->setCss('base.css');
	echo $loader->View->setCss('index.css'); 
	echo $loader->View->setScript('jquery.jscroll.min.js');
	echo $loader->View->setScript('jquery.autoexpand.js');
	echo $loader->View->setScript('js-micropost.js');
	include_once($loader->View->getElement('nav')); 
?>
	<div class="container">
		<div class="row">
			<div id="micropost" class="col-xs-10 col-xs-offset-1">
				<?php if(!empty($NoticeCollection )): ?>
					<div id="ovarall-micropost-space">
						<div class="each-paginate-micropost-space">
							<?php foreach($NoticeCollection as $NoticeArray): ?>
								<div data-toggle="modal" data-replay-id="<?php echo $NoticeArray['replay_id']; ?>"  data-micropost-id="<?php echo $NoticeArray['micropost_id']?>" class="each-micropost-space" data-recipient="<?php echo $User->getNameById($NoticeArray['other_user_id']); ?>" data-user-id="<?php echo $NoticeArray['other_user_id'];?>">
									<div class="micropost-content-space">
										<div class="micropost-user-info">
											<?php $center = ($NoticeArray['type'] !== 'replay')? 'class="text-center"' : ''; ?>
											<p <?php echo $center; ?> ><span class="glyphicon glyphicon-user" aria-hidden="true"></span><?php echo $NoticeArray['title']; ?></p>
										</div>
										<div class="micropost-content" >
											<p>
												<?php echo $NoticeArray['content']; ?>
											</p>
										</div>
									</div>
									<?php if($NoticeArray['type'] === 'replay'): ?>
										<div class="micropost-content-button">
											<div class="micropost-replay-space">
												<button  data-toggle="tooltip" title="返信" class="micropost-replay-button" data-recipient="<?php echo $UserArray['name']; ?>"><i class="fa fa-reply" aria-hidden="true"></i></button>
											</div>
											<!-- <div class="micropost-retweet-space">
												<button data-toggle="tooltip" title="リツイート"  class="micropost-retweet-button"><i class="fa fa-exchange" aria-hidden="true"></i></button>
											</div> -->
											<div class="micropost-favorite-space">
												<?php $checkFavorite = $Favorite->isFavoriteByReplayIdAndUserId($NoticeArray['replay_id'],$NoticeArray['user_id']); ?>
												<button data-toggle="tooltip" data-target="#replayModal" data-type="add" title="いいね" class="micropost-favorite-button" 
													<?php if($checkFavorite){ echo 'style ="display: none;"';} ?>
												>
													<i class="fa fa-heart" aria-hidden="true"></i>
												</button>
												<button data-toggle="tooltip" data-type="delete" title="いいね取り消し" class="delete-micropost-favorite-button" 
													<?php if(!$checkFavorite){ echo 'style ="display: none;"';} ?>
												>
													<i class="fa fa-heart" aria-hidden="true"></i>
												</button>
												<span class="favorite-count"><?php echo $Favorite->getCountByReplayId($NoticeArray['replay_id']);?></span>
											</div>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
							<div class="test-scroll">
								<?php if($page != $maxPages): ?>
									<a href="<?php echo $loader->SiteSetting->getUrl() . '/User/notice.php?page=' .  $nextPage; ?>">
										次のページ
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>	
				<?php endif; ?>	
			</div>
		</div>
	</div>
	<?php include_once($loader->View->getElement('modal')); ?>
</body>
</html>