<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/Micropost.php');
	$metaData = [
		'title' => 'Home'
	];
	$loader = new Loader;
	$User = new User;
	$Micropost = new Micropost;
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
<div id="js-micropost-provider" data-micropost-post-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/View/Ajax/post_micropost.php';?>"></div>
<?php 
	echo $loader->View->setCss('base.css');
	echo $loader->View->setCss('index.css'); 
	echo $loader->View->setScript('jquery.jscroll.min.js');
	include_once($loader->View->getElement('nav')); 
?>
<script>
$(function(){
	var $dataMicropostProvider = $('#js-micropost-provider'),
		$jsMicropostTextForm = $('#js-micropost-text-form'),
		$jsMicropostTextArea = $('#js-micropost-textarea'),
		$jsMicropostFormButton = $('#micropost-form-bottom'),
		$jsSubmitMicropsot = $('.submit-micropost');

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

	$jsMicropostTextArea.on('keyup',function(e){
		if(hasMicropostFormValue(e.target.value)){
			$jsSubmitMicropsot.prop('disabled',false);
		}else{
			$jsSubmitMicropsot.prop('disabled',true);
		}
	})

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
		}).fail(function(xhr, textStatus, errorThrown){
			alert('追加に失敗しました。');
			console.log(xhr);
			console.log(textStatus);
			console.log(errorThrown);
		});
	});

	function toggleMicropostForm()
	{
		$jsMicropostTextForm.toggle();
		$jsMicropostTextArea.toggle();
		$jsMicropostFormButton.toggle();
	}

	function hasMicropostFormValue ($val)
	{
		return ($val !== '')? true : false;
	}

	$('#ovarall-micropost-space').jscroll({
		nextSelector : '.test-scroll a',
		contentSelector : '.each-paginate-micropost-space',
		loadingHtml: '<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>'
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
							<input  id="js-micropost-text-form" type="text" placeholder="つぶやく" class="form-control">
							<textarea id="js-micropost-textarea" name="data[Micropost][content]" class="form-control" placeholder="つぶやく" rows="5" style="display: none"></textarea>
						</div> 
						<div id="micropost-form-bottom" class="text-right" style="display: none">
							<button type="button" class="submit-micropost btn btn-warning" disabled="true" >投稿</button>
						</div>
					</form>
				</div>
					
				<div id="ovarall-micropost-space">
					<div class="each-paginate-micropost-space">
						<?php foreach($MicropostCollection as $MicropostArray): ?>
							<div id="micropost_id-<?php echo $MicropostArray['id']?>" class="each-micropost-space">
								<div class="micropost-user-info">
									<p><span>画像</span><?php echo $UserArray['name']; ?></p>
								</div>
								<div class="micropost-content">
									<p>
										<?php echo $loader->View->h($MicropostArray['content']); ?>
									</p>
								</div>
								<div class="micropost-content-button">
									<span>いいね</span><span>リツイート</span><span>返信</span>
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
	</body>
</html>