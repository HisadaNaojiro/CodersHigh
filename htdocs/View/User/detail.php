<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Favorite.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Follow.php');



	$metaData = [
		'title' => 'Home'
	];
	$loader = new Loader;
	$User = new User;
	$Micropost = new Micropost;
	$Favorite = new Favorite;
	$Follow = new Follow;
	$loader->SiteSetting->setMetaData($metaData);
	$message =!empty($loader->Session->get('message'))?$loader->Session->get('message') : '';
	$loader->Session->remove('message');
	$UserArray = $User->getArrayByName($_GET['name']);
	$currentUserId  = $loader->Session->get('user_id');
	$maxPages = ceil($Micropost->getCount()  / 10);
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$nextPage = $page + 1;
	$MicropostCollection = $Micropost->getCollectionByUserIdForPaginate($UserArray['id'],$page);
	
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
			<?php if(!empty($message)): ?>
				<div class="alert alert-info" role="alert"><?php echo $message; ?></div>
			<?php endif; ?>
			<div id="user-info" class="col-md-3">
				<div class="user-profile col-xs-12">
					<div></div>
					<div class="col-xs-6 user-image">
						<p style="font-size: 80px;"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></p>
					</div>
					<div class="col-xs-6 user-name">
						<p  style="font-size: 20px;"><?php echo $UserArray['name']; ?></p>
					</div>
				</div>
				<div class="user-info-link col-xs-12">
					<span class="col-xs-4">
						<a href="<?php echo $loader->SiteSetting->getUrl() . '/User/detail.php?name=' . $UserArray['name']; ?>">
							ツイート
						</a>
					</span>
					<span class="col-xs-4">
						<a href="<?php echo $loader->SiteSetting->getUrl() . '/User/follow.php?name=' . $UserArray['name']; ?>">
							フォロー
						</a>
					</span>
					<span class="col-xs-4">
						<a href="<?php echo $loader->SiteSetting->getUrl() . '/User/followed.php?name=' . $UserArray['name']; ?>">
							フォロワー
						</a>
					</span>
				</div>
				<?php if($currentUserId != $UserArray['id']): ?>
					<div id="follow-space" data-user-id="<?php echo $UserArray['id']?>">
						<?php $isFollow = $Follow->isFollow($currentUserId , $UserArray['id']); ?>
						<button  data-type="follow" class="btn btn-warning btn-block js-follow-button follow" <?php if($isFollow){ echo 'style ="display: none;"';} ?> >
							フォロー
						</button>
					
						<button data-type="unfollow" class="btn btn-default btn-block js-follow-button unfollow" <?php if(!$isFollow){ echo 'style ="display: none;"';} ?>>
							アンフォロー
						</button>
					</div>
				<?php endif; ?>
			</div>
			<div id="micropost" class="col-md-8 col-md-offset-1">
				<div id="ovarall-micropost-space">
					<div class="each-paginate-micropost-space">
						<?php foreach($MicropostCollection as $MicropostArray): ?>
							<div data-toggle="modal"  data-micropost-id="<?php echo $MicropostArray['id']?>" class="each-micropost-space each-micropost-border" data-recipient="<?php echo $UserArray['name']; ?>" data-user-id="<?php echo $UserArray['id']; ?>">
								<div class="micropost-content-space">
									<div class="micropost-user-info">
										<p><span class="glyphicon glyphicon-user" aria-hidden="true"></span><?php echo $UserArray['name']; ?></p>
									</div>
									<div class="micropost-content" >
										<p>
											<?php echo $loader->View->h($MicropostArray['content']); ?>
										</p>
									</div>
								</div>
								<div class="micropost-content-button">
									<div class="micropost-replay-space">
										<button  data-toggle="tooltip" title="返信" class="micropost-replay-button" data-recipient="<?php echo $UserArray['name']; ?>"><i class="fa fa-reply" aria-hidden="true"></i></button>
									</div>
									<!-- <div class="micropost-retweet-space">
										<button data-toggle="tooltip" title="リツイート"  class="micropost-retweet-button"><i class="fa fa-exchange" aria-hidden="true"></i></button>
									</div> -->
									<div class="micropost-favorite-space">
										<?php $checkFavorite = $Favorite->isFavoriteByMicropostIdAndUserId($MicropostArray['id'],$UserArray['id']); ?>
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
										<span class="favorite-count"><?php echo $Favorite->getCountByMicropostId($MicropostArray['id']);?></span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<div class="test-scroll">
							<?php if($page != $maxPages): ?>
								<a href="<?php echo $loader->SiteSetting->getUrl() . '/User/detail.php?name=' . $UserArray['name'] . '&page=' . $nextPage; ?>">
									次のページ
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>		
			</div>
		</div>
	</div>
	<?php include_once($loader->View->getElement('modal')); ?>
</body>
</html>