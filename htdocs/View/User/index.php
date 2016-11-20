<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Favorite.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Replay.php');

	$metaData = [
		'title' => 'Home'
	];
	$loader = new Loader;
	$User = new User;
	$Micropost = new Micropost;
	$Favorite = new Favorite;
	$Replay = new Replay;
	$loader->SiteSetting->setMetaData($metaData);
	$message =!empty($loader->Session->get('message'))?$loader->Session->get('message') : '';
	$loader->Session->remove('message');
	$UserArray = $User->getArrayById($loader->Session->get('user_id'));
	$maxPages = ceil($Micropost->getCount()  / 10);
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$nextPage = $page + 1;
	$MicropostCollection = $Micropost->getCollectionForPaginate($UserArray['id'],$page);
	
	include_once($loader->View->getLayout('header'));

	
?>
<body>
<div id="js-micropost-provider" 
	data-micropost-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_micropost.php';?>" 
	data-favorite-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_favorite.php';?>"
	data-replay-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_replay.php';?>"
	data-replay-get-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/get_replay.php';?>"
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
			<div id="user-info" class="col-xs-3">
				<div class="user-profile col-xs-12">
					<div></div>
					<div class="col-xs-6 user-image">
						<p>画像</p>
					</div>
					<div class="col-xs-6 user-name">
						<p><?php echo $UserArray['name']; ?></p>
					</div>
				</div>
				<div class="user-info-link col-xs-12">
					<span>ツイート</span><span>フォロー</span><span>フォロワー</span>
				</div>
			</div>
			<div id="micropost" class="col-xs-8 col-xs-offset-1">
				
				<div class="micropost-form-space bg-warning">
					<form action="" method="POST" class="form-inline">
						<div class="micropost-form-image col-xs-2">画像</div>
						<div class="input-group col-xs-10">
							<input  id="js-micropost-text-form" type="text" placeholder="つぶやく" class="form-control">
							<textarea  class="form-control js-micropost-textarea" placeholder="つぶやく" rows="5" style="display: none"></textarea>
						</div> 
						<div id="micropost-form-bottom" class="text-right" style="display: none">
							<button type="button" class="submit-micropost btn btn-warning" disabled="true" >投稿</button>
						</div>
					</form>
				</div>
					
				<div id="ovarall-micropost-space">
					<div class="each-paginate-micropost-space">
						<?php foreach($MicropostCollection as $MicropostArray): ?>
							<div data-toggle="modal"  data-micropost-id="<?php echo $MicropostArray['m_id']?>" class="each-micropost-space" data-recipient="<?php echo $MicropostArray['u_name']; ?>" data-user-id="<?php echo $MicropostArray['m_user_id']; ?>">
								<div class="micropost-content-space">
									<div class="micropost-user-info">
										<p><span class="glyphicon glyphicon-user" aria-hidden="true"></span><?php echo $MicropostArray['u_name']; ?></p>
									</div>
									<div class="micropost-content" >
										<p>
											<?php echo $loader->View->h($MicropostArray['m_content']); ?>
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
										<?php $checkFavorite = $Favorite->isFavoriteByMicropostIdAndUserId($MicropostArray['m_id'],$MicropostArray['m_user_id']); ?>
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
										<span class="favorite-count"><?php echo $Favorite->getCountByMicropostId($MicropostArray['m_id']);?></span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<div class="test-scroll">
							<?php if($page != $maxPages): ?>
								<a href="?page=<?php echo $nextPage; ?>" >次のページ</a>
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
