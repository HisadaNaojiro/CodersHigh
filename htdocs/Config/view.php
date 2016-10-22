<?php

class View
{
	private $__layout = '/View/Layout/';
	private $__element = '/View/Element/';
	
	public function getLayout($file)
	{
		return $_SERVER['DOCUMENT_ROOT'] . $this->__layout . $file . '.php';
	}

	public function getElement($file)
	{
		return $_SERVER['DOCUMENT_ROOT'] . $this->__element . $file . '.php';
	}

	public function setScript($value)
	{
		$path = 'http://' . $_SERVER['HTTP_HOST'] . '/webroot/js/' . $value;

		return '<script type="text/javascript" src="' . $path . '"></script>';
	}

	public function setCss($value)
	{
		$path = 'http://' . $_SERVER['HTTP_HOST'] . '/webroot/css/' . $value;

		return '<link rel="stylesheet" type="text/css" href="' . $path .'" />';
	}

	public function setImage($value,$options = array())
	{
		$path = 'http://' . $_SERVER['HTTP_HOST'] . '/webroot/img/' . $value;
		$class = isset($options['class'])? 'class="' . $options["class"] . '"' : "";
		$id = isset($options['id'])? 'id="' . $options["id"] . '"' : "";
		$alt = isset($options['alt'])? 'alt="' . $options["alt"] . '"' : "";
		$with = isset($options['with'])? 'with="' . $options['with'] . '"'  : "";
		$height = isset($options['height'])? 'height="' . $options["height"] . '"' : "";
		$style = isset($options['style'])? 'style="' . $options["style"] . '"' : "";

		return "<img src='" . $path . "' {$id} {$class}  {$alt} {$with} {$height} {$style}/>";
	}

	public function h($value)
	{
		return htmlspecialchars($value,ENT_QUOTES);
	}

}