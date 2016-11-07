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
	