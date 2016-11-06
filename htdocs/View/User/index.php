<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Favorite.php');

	$metaData = [
		'title' => 'Home'
	];
	$loader = new Loader;
	$User = new User;
	$Micropost = new Micropost;
	$Favorite = new Favorite;
	$loader->SiteSetting->setMetaData($metaData);
	$message =!empty($loader->Session->get('message'))?$loader->Session->get('message') : '';
	$loader->Session->remove('message');
	$UserArray = $User->getUserArrayById($loader->Session->get('user_id'));
	$maxPages = ceil($Micropost->getCount()  / 10);
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$nextPage = $page + 1;
	$MicropostCollection = $Micropost->getCollectionForPaginate($page);
	
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
	include_once($loader->View->getElement('nav')); 
?>
<script>
$(function(){
	var $dataMicropostProvider = $('#js-micropost-provider'),
		$jsMicropostTextForm = $('#js-micropost-text-form'),
		$jsMicropostTextArea = $('.js-micropost-textarea'),
		$jsMicropostFormButton = $('#micropost-form-bottom'),
		$jsSubmitReplay		= $('.submit-replay'),
		$jsSubmitMicropsot = $('.submit-micropost'),
		$jsReplayDefaultLength = null;

	$jsMicropostTextForm.on('click',function(){
		toggleMicropostForm();
	});
	
	$(document).on('click', function(e) {
	  if (
	  		!$(e.target).closest('.micropost-form-space').length && 
	  		$jsMicropostTextArea.css('display') === 'block'
	  ) {
	    	toggleMicropostForm();
	  }
	});

	//ツイート
	$jsMicropostTextArea.on('keyup',function(e){
		(e.target.value !== '') ? $jsSubmitMicropsot.prop('disabled',false) : $jsSubmitMicropsot.prop('disabled',true);
	})

	//ツイート追加
	$jsSubmitMicropsot.on('click',function(){
		toggleMicropostForm();
		$jsSubmitMicropsot.prop('disabled',true);
		var data = {"data[Micropost][content]" : $jsMicropostTextArea.val()};
		$jsMicropostTextArea.val('');
		$.ajax({
			'type'		: 'POST',
			'url'		: $dataMicropostProvider.data('micropostPostUrl'),
			'data'		: data,
			'dataType'	: 'json'
		}).done(function(data){

			$(data['text']).prependTo('#ovarall-micropost-space');
		}).fail(function(){
			alert('追加に失敗しました');
		});
	});

	//いいね追加
	$('.micropost-favorite-button,.delete-micropost-favorite-button').on('click',function(e){
		$event = $(e.target);
		var value = $event.parents('.each-micropost-space').data('micropostId'),
			type = $event.parent().data('type');
		var data = {"data[Favorite][micropost_id]" : value,"type" : type};
		$.ajax({
			'type'		: 'POST',
			'url'		: $dataMicropostProvider.data('favoritePostUrl'),
			'data' 		: data,
			'dataType'	:'json'
		}).done(function(data){
			$parents = $event.parents('.micropost-favorite-space');
			$parents.children('.micropost-favorite-button').toggle();
			$parents.children('.delete-micropost-favorite-button').toggle();
			$parents.children('.favorite-count').text(data);
		}).fail(function(){
			alert('いいねに失敗しました');
		});
		e.stopPropagation();
	});

	function toggleMicropostForm()
	{
		$jsMicropostTextForm.toggle();
		$jsMicropostTextArea.toggle();
		$jsMicropostFormButton.toggle();
	}

	function toggleFavoriteButton()
	{
		$('.micropost-favorite-button').toggle();
		$('.delete-micropost-favorite-button').toggle();

	}

	$('#ovarall-micropost-space').jscroll({
		nextSelector : '.test-scroll a',
		contentSelector : '.each-paginate-micropost-space',
		loadingHtml: '<div id="loading-data" class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
	});

	$('[data-toggle="tooltip"]').tooltip();


	//返信のモーダル表示
	$('.micropost-replay-button').on('click',function(e){
		$event = $(e.target);
		$modal = $('#replayModal');
		var recipient = $event.parent().data('recipient'),
			content = $event.parents('.each-micropost-space').children('.micropost-content-space').html(),
			detailLink = $dataMicropostProvider.data('micropostDetailUrl');
		var html = '<a href="' + detailLink + '?name=' + recipient + '">' + recipient + '</a>';
		$modal.find('.modal-body > div').attr('data-micropost-id',$event.parents('.each-micropost-space').data('micropost-id'));
		$modal.find('.modal-title').text(recipient + 'へのメッセージ');
		$modal.find('.modal-body > div').html(content);
		$modal.find('.js-micropost-replay').html(html);
		$jsSubmitReplay.attr('disabled',true)
		$jsReplayDefaultLength = $modal.find('.js-micropost-replay').text().length;
		$modal.modal();
		e.stopPropagation();
	});

	//返信テキストの高さ自動調節	
	$('.js-micropost-replay').autoExpand();

	//返信の文字入力チェック
	$('.js-micropost-replay').on('keyup',function(e){
		length = $(e.target).text().length - $jsReplayDefaultLength;
		(length > 0 )? $jsSubmitReplay.attr('disabled',false) : $jsSubmitReplay.attr('disabled',true);
	});

	//返信の追加
	$('.submit-replay').on('click',function(e){
		$event = $(e.target);
		if($event.attr('disabled')){
			return ;
		}
		$modalContent = $event.parents('.modal-content');
		
		var data = {
				"data[Replay][content]" : $modalContent.find('.js-micropost-replay').html(),
				"data[Replay][micropost_id]" : $modalContent.find('.modal-micropost-space').data('micropostId')
		};
		$.ajax({
			'type'		: 'POST',
			'url'		: $dataMicropostProvider.data('replayPostUrl'),
			'data' 		: data,
			'dataType'	:'json'
		}).done(function(data){
			$('.alerinfo').remove();
			$('body').find('.row').prepend('<div class="alert alert-info" role="alert">メッセーシを返信しました</div>');
		}).fail(function(a,b,c){
			alert('返信に失敗しました');
		});
	});
	//ここのコンテンツ表示
	$('.each-micropost-space').on('click',function(){
		$this = $(this);
		$modal = $('#micropostContentModal');
		$modalMicropostSpace = $modal.find('.modal-micropost-space');
		var recipient = $this.data('recipient'),
			content = $this.children('.micropost-content-space').html(),
			detailLink = $dataMicropostProvider.data('micropostDetailUrl');
		var html = '<a href="' + detailLink + '?name=' + recipient + '">' + recipient + '</a>';
		$modalMicropostSpace.attr('data-micropost-id',$this.data('micropost-id'));
		$modalMicropostSpace.html(content);
		$modalMicropostSpace.prepend('<button type="button" class="close" data-dismiss="modal"><span>×</span></button>');
		$modal.find('.js-micropost-replay').html(html);
		$jsSubmitReplay.attr('disabled',true)
		$jsReplayDefaultLength = $modal.find('.js-micropost-replay').text().length;
		data = {"data[Replay][micropost_id]" : $this.data('micropost-id')};
		$.ajax({
			'type'		: 'GET',
			'url'		: $dataMicropostProvider.data('replayGetUrl'),
			'data' 		: data,
			'dataType'	:'json'
		}).done(function(data){
			$('.modal-replay-space').remove()
			if(data != 'nothing'){
				$modal.find('.modal-content').append('<div class="modal-footer modal-replay-space">');

				$('.modal-replay-space').html(data);
			}
			$modal.modal();
		}).fail(function(){
			alert('返信の取得に失敗しました');
		});
		
	});

	
});
	
</script>
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
							<div data-toggle="modal"  data-micropost-id="<?php echo $MicropostArray['id']?>" class="each-micropost-space" data-recipient="<?php echo $UserArray['name']; ?>">
								<div class="micropost-content-space">
									<div class="micropost-user-info">
										<p><span>画像</span><?php echo $UserArray['name']; ?></p>
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
										<?php $checkFavorite = $Favorite->isFavoriteByUserId($MicropostArray['id'],$UserArray['id']); ?>
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