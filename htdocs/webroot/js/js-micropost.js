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
		$micropostSpace = $event.parents('.each-micropost-space');
		var data = {
				"data[Favorite][micropost_id]" : $micropostSpace.data('micropostId'),
				"data[Favorite][replay_id]" : $micropostSpace.data('replayId'),
				"data[Favorite][user_id]" : $micropostSpace.data('userId'),
				"type" : $event.parent().data('type')
		};
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
		$micropostSpace = $event.parents('.each-micropost-space');
		var recipient = $micropostSpace.data('recipient'),
			content = $micropostSpace.children('.micropost-content-space').html(),
			detailLink = $dataMicropostProvider.data('micropostDetailUrl');
		var html = '<a href="' + detailLink + '?name=' + recipient + '">' + recipient + '</a>';
		$modal.find('.modal-content').attr('data-user-id',$micropostSpace.data('userId'));
		$modal.find('.modal-body > div').attr('data-micropost-id',$micropostSpace.data('micropostId'));
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
				"data[Replay][micropost_id]" : $modalContent.find('.modal-micropost-space').data('micropostId'),
				"data[Replay][user_id]" : $modalContent.data('userId')
		};
		$.ajax({
			'type'		: 'POST',
			'url'		: $dataMicropostProvider.data('replayPostUrl'),
			'data' 		: data,
			'dataType'	:'json'
		}).done(function(data){
			$('.alert-info').remove();
			$('body').find('.row').prepend('<div class="alert alert-info" role="alert">メッセーシを返信しました</div>');
		}).fail(function(a,b,c){
			alert('返信に失敗しました');
		});
	});

	//ここのツイート表示
	$('.each-micropost-space').on('click',function(){
		$this = $(this);
		$modal = $('#micropostContentModal');
		$modalMicropostSpace = $modal.find('.modal-micropost-space');
		var recipient = $this.data('recipient'),
			content = $this.children('.micropost-content-space').html(),
			replayId = $this.data('replayId');
			detailLink = $dataMicropostProvider.data('micropostDetailUrl');
		var html = '<a href="' + detailLink + '?name=' + recipient + '">' + recipient + '</a>';
		$modalMicropostSpace.attr('data-micropost-id',$this.data('micropostId'));
		$modal.find('.modal-content').attr('data-user-id',$this.data('userId'));
		$modalMicropostSpace.attr('data-replay-id',replayId);
		$modalMicropostSpace.prepend('<button type="button" class="close" data-dismiss="modal"><span>×</span></button>');
		$modal.find('.js-micropost-replay').html(html);
		$jsSubmitReplay.attr('disabled',true)
		$jsReplayDefaultLength = $modal.find('.js-micropost-replay').text().length;
		data = {"data[Replay][micropost_id]" : $this.data('micropostId') ,"replay_id" : $this.data('replayId')};
		$.ajax({
			'type'		: 'GET',
			'url'		: $dataMicropostProvider.data('replayGetUrl'),
			'data' 		: data,
			'dataType'	:'json'
		}).done(function(data){
			$('.modal-replay-space').remove()
			$modalMicropostSpace.html(data['Micropost']);
			if(data['Replay'] != 'nothing'){
				$modal.find('.modal-content').append('<div class="modal-footer modal-replay-space">');
				if(!replayId){
					$('.modal-replay-space').html(data['Replay']);
				}else{
					$(data['Replay']).each(function(index,element){

						if($(element).data('replayId') >= replayId){
							$('.modal-replay-space').append(element);
						}else{
							$modalMicropostSpace.append(element);
						}
					});
				}
				
			}
			$modal.modal();
		}).fail(function(){
			alert('返信の取得に失敗しました');
		});
		
	});

	//follow/unfollow
	$('.js-follow-button').on('click',function(e){
		$event = $(e.target);
		type = $event.data('type');
		value = $event.parent().data('userId');
		data = {"data[Follow][followed_id]" : value , "type" : type};
		$.ajax({
			'type'		: 'POST',
			'url'		: $dataMicropostProvider.data('followPostUrl'),
			'data' 		: data,
			'dataType'	:'json'
		}).done(function(data){
			$('.js-follow-button').toggle();
		}).fail(function(a,b,c){
			alert('返信に失敗しました');
		});
	});


	
});
	