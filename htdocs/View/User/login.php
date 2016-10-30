<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');

	$loader  = new Loader();
	$metaData = [
			'title' =>'ログイン画面',
			'keywords' =>'ログイン',
			'description' => 'ログイン画面',
			'h1' => 'ログイン画面'
	];
	if($loader->Request->isPost()){
		$User = new User();
		$formData = $_POST['data'];
		$User->set($formData);

		if($UserArray = $User->authenticate()){
			$loader->Session->set('user_id',$UserArray['id']);
			$loader->Session->set('message','ログインしました。');
			$loader->Request->redirect('User/index');
		}
		$errors = $User->ValidationErrors;
	}
	$loader->SiteSetting->setMetaData($metaData);
	include_once($loader->View->getLayout('header'));
	include_once($loader->View->getElement('nav'));
?>

<div class="container">
    <div class="row">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">ログイン</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form  method="POST" action="" role="form">
                    <fieldset>
			    	  	<div class="form-group">
			    		    <input class="form-control" placeholder="yourmail@example.com" name="data[User][email]" type="text">
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" placeholder="Password" name="data[User][password]" type="password" value="">
			    		</div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>
<?php include_once($loader->View->getLayout('footer')); ?>
