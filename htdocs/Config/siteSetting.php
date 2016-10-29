<?php

class SiteSetting
{

	protected $__title = null;
	protected $__keywords = null;
	protected $__description = null;
	protected $__h1 = null;


	public function setMetaData($params)
	{

		$this->__title = isset($params["title"])? $params["title"] : '';
		$this->__keywords = isset($params["keywords"])? $params["keywords"] : '';
		$this->__description = isset($params["description"])? $params["description"] : '';
		$this->__h1 = isset($params["h1"])? $params["h1"] : '';
	}

	public function getTitle()
	{
		return $this->__title;
	}

	public function getKeywords()
	{
		return $this->__keywords;
	}

	public function getDescription()
	{
		return $this->__description;
	}

	public function getH1()
	{
		return $this->__h1;
	}

	public function getRootUrl()
	{
		return "http" . $_SERVER['HTTP_HOST'];
	}

}
