<!DOCTYPE html>
<html lang='ja'>
<head>
	<meta charset='UTF-8'>

	<meta name='keywords' content='<?php echo $loader->SiteSetting->getkeywords();?>' >
	<meta name="description" content='<?php echo $loader->SiteSetting->getDescription();?>' />
	<title><?php echo $loader->SiteSetting->getTitle(); ?></title>

	<?php echo $loader->View->setCss('bootstrap.min.css'); ?>
	<?php echo $loader->View->setScript('jquery-2.2.4.min.js'); ?>
	<?php echo $loader->View->setScript('bootstrap.min.js'); ?>
</head>
<body>
