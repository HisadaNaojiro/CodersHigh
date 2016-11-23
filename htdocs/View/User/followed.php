<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Follow.php');

	$metaData = [
		'title' => 'Home'
	];
	$loader = new Loader;
	$User = new User;
	$Follow = new Follow;


	$loader->SiteSetting->setMetaData($metaData);
	$message =!empty($loader->Session->get('message'))?$loader->Session->get('message') : '';
	$loader->Session->remove('message');
	$UserArray = $User->getArrayByName($_GET['name']);
	$FollowedUserCollection = $User->getCollectionByFollowedId($UserArray['id']);
	// $maxPages = ceil($Micropost->getCount()  / 10);
	// $page = isset($_GET['page']) ? $_GET['page'] : 1;
	// $nextPage = $page + 1;
	// $MicropostCollection = $Micropost->getCollectionForPaginate($UserArray['id'],$page);
	
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
						<p><?php echo $UserArray['name']; ?></p>
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
						フォロワー
					</span>
				</div>
			</div>
			<div class="col-md-8 col-md-offset-1">
				<?php foreach($FollowedUserCollection as $FollowedUserArray): ?>
					<div class="col-xs-6 col-md-4">
						<div class="thumbnail">
							<div class="text-center">
								<span style="font-size: 120px;" class="glyphicon glyphicon-user" aria-hidden="true"></span>
							</div>
							<div class="caption">
								<h3><?php echo $loader->View->h($FollowedUserArray['name']);?></h3>
								<div id="follow-space" data-user-id="<?php echo $FollowedUserArray['id']?>">
									<?php $isFollow = $Follow->isFollow($UserArray['id'] , $FollowedUserArray['id']); ?>
									<button  data-type="follow" class="btn btn-warning btn-block js-follow-button follow" <?php if($isFollow){ echo 'style ="display: none;"';} ?> >
										フォロー
									</button>
								
									<button data-type="unfollow" class="btn btn-default btn-block js-follow-button unfollow" <?php if(!$isFollow){ echo 'style ="display: none;"';} ?>>
										アンフォロー
									</button>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php include_once($loader->View->getElement('modal')); ?>
</body>
</html>
