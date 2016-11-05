<!DOCTYPE html>
<html lang='ja'>
<head>
	<meta charset='UTF-8'>

	<?php if(!empty($loader->SiteSetting->getkeywords() )): ?>
		<meta name='keywords' content='<?php echo $loader->SiteSetting->getkeywords();?>' >
	<?php endif; ?>
	<?php if(!empty($loader->SiteSetting->getDescription() )): ?>
		<meta name="description" content='<?php echo $loader->SiteSetting->getDescription();?>' />
	<?php endif; ?>

	<?php if(!empty($loader->SiteSetting->getTitle() )): ?>
		<title><?php echo $loader->SiteSetting->getTitle(); ?></title>
	<?php endif; ?>

	<?php echo $loader->View->setCss('bootstrap.min.css'); ?>
	<?php echo $loader->View->setCss('bootstrap-theme.min.css'); ?>
	<?php echo $loader->View->setCss('font-awesome.min.css'); ?>
	<?php echo $loader->View->setCss('theme.css'); ?>
	<?php echo $loader->View->setScript('jquery-2.2.4.min.js'); ?>
	<?php echo $loader->View->setScript('bootstrap.min.js'); ?>
</head>

