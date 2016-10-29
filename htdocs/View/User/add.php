<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Config/loader.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/Model/User.php');

	$loader  = new Loader();
	$metaData = [
			'title' =>'新規登録',
			'h1' => '新規登録'
	];
	$loader->SiteSetting->setMetaData($metaData);

	
	if($loader->Request->isPost()){


		$User = new User();
		$formData = $_POST['data'];
		$User->set($formData);
		
		if($User->validates()){
			if($id = $User->save($formData)){
				$loader->Session->set('user_id',$id);
				$loader->Request->redirect('User/index');
			}
		}

		$errors = $User->ValidationErrors;
	}
	include_once($loader->View->getLayout('header'));
	include_once($loader->View->getElement('nav'));
?>
<div class="container">
	<div class="page-header">
		<h1><?php echo $loader->SiteSetting->getH1(); ?></h1>
	</div>
	<form action="" method="post" class="form-horizontal">
		<div class="form-group <?php echo !empty($errors['User']['name'])? 'has-error' : '' ?>">
			<label class="col-sm-2 control-label" for="InputEmail">ニックネーム</label>
			<div class="col-sm-10">
				<input type="text" name="data[User][name]" class="form-control" placeholder="コーダーズ太郎">
				<?php if(!empty($errors['User']['name'])): ?>
					<div class="text-danger"><?php echo $errors['User']['name']; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-group <?php echo !empty($errors['User']['email'])? 'has-error' : '' ?>">
			<label class="col-sm-2 control-label" for="InputEmail">メールアドレス</label>
			<div class="col-sm-10">
				<input type="email" name="data[User][email]" class="form-control" placeholder="corders@example.com">
				<?php if(!empty($errors['User']['email'])): ?>
					<div class="text-danger"><?php echo $errors['User']['email']; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-group <?php echo !empty($errors['User']['emailConfirmation'])? 'has-error' : '' ?>">
			<label class="col-sm-2 control-label" for="InputEmailConfirmation">確認用メールアドレス</label>
			<div class="col-sm-10">
				<input type="email" name="data[User][emailConfirmation]" class="form-control" placeholder="corders@example.com">
				<?php if(!empty($errors['User']['emailConfirmation'])): ?>
					<div class="text-danger"><?php echo $errors['User']['emailConfirmation']; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-group <?php echo !empty($errors['User']['password'])? 'has-error' : '' ?>">
			<label class="col-sm-2 control-label" for="InputPassword">パスワード</label>
			<div class="col-sm-10">
				<input type="password" name="data[User][password]" class="form-control" placeholder="Passowrd">
				<?php if(!empty($errors['User']['password'])): ?>
					<div class="text-danger"><?php echo $errors['User']['password']; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-group <?php echo !empty($errors['User']['passwordConfirmation'])? 'has-error' : '' ?>">
			<label class="col-sm-2 control-label" for="InputPasswordConfirmation">確認用パスワード</label>
			<div class="col-sm-10">
				<input type="password" name="data[User][passwordConfirmation]" class="form-control" placeholder="Passowrd">
				<?php if(!empty($errors['User']['passwordConfirmation'])): ?>
					<div class="text-danger"><?php echo $errors['User']['passwordConfirmation']; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-success">登録</button>
			</div>
		</div>
	</form>
</div>