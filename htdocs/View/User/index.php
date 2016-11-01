<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
	$metaData = [
		'title' => 'Home'
	];
	$loader = new Loader();
	$User = new User();
	$loader->SiteSetting->setMetaData($metaData);
	$message =!empty($loader->Session->get('message'))?$loader->Session->get('message') : '';
	$loader->Session->remove('message');
	$UserArray = $User->getUserArrayById($loader->Session->get('user_id'));
	include_once($loader->View->getLayout('header'));
	
?>
<body>
<?php 
	echo $loader->View->setCss('base.css');
	echo $loader->View->setCss('index.css'); 
	include_once($loader->View->getElement('nav')); 
?>
	<div class="container">
		<div class="row">
			<?php if(!empty($message)): ?>
				<div class="alert alert-info" role="alert"><?php echo $message; ?></div>
			<?php endif; ?>
			<div id="user-info" class="col-xs-3">
				<div class="user-profile col-xs-12">
					<div class="col-xs-6">
						<p>画像</p>
					</div>
					<div class="col-xs-6">
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
							<input type="text" placeholder="テキスト入力" class="form-control">
						</div>
						<div class="micropost-form-bottom text-right">
							<button type="submit" class="btn btn-warning">投稿</button>
						</div>
					</form>
				</div>
					
				<div class="ovarall-micropost-space">
					<div class="each-micropost-space">
						<div class="micropost-user-info">
							<p><span>画像</span><?php echo $UserArray['name']; ?></p>
						</div>
						<div class="micropost-content">
							<p>
								テキストテキストテキストテキストテキストテキストテキスト
							</p>
						</div>
						<div class="micropost-content-button">
							<span>いいね</span><span>リツイート</span><span>返信</span>
						</div>
						
					</div>
					<div class="each-micropost-space">
						<div class="micropost-user-info">
							<p><span>画像</span><?php echo $UserArray['name']; ?></p>
						</div>
						<div class="micropost-content">
							<p>
								テキストテキストテキストテキストテキストテキストテキスト
							</p>
						</div>
						<div class="micropost-content-button">
							<span>いいね</span><span>リツイート</span><span>返信</span>
						</div>
						
					</div>
					<div class="each-micropost-space">
						<div class="micropost-user-info">
							<p><span>画像</span><?php echo $UserArray['name']; ?></p>
						</div>
						<div class="micropost-content">
							<p>
								テキストテキストテキストテキストテキストテキストテキスト
							</p>
						</div>
						<div class="micropost-content-button">
							<span>いいね</span><span>リツイート</span><span>返信</span>
						</div>
						
					</div>
					<div class="each-micropost-space">
						<div class="micropost-user-info">
							<p><span>画像</span><?php echo $UserArray['name']; ?></p>
						</div>
						<div class="micropost-content">
							<p>
								テキストテキストテキストテキストテキストテキストテキスト
							</p>
						</div>
						<div class="micropost-content-button">
							<span>いいね</span><span>リツイート</span><span>返信</span>
						</div>
						
					</div>
				</div>		
			</div>
		</div>
	</div>
	</body>
</html>