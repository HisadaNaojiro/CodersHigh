<?php

class SiteSetting
{

	protected $__title = null;
	protected $__keywords = null;
	protected $__description = null;


	public function setMetaData($params)
	{

		$this->__title = $params["title"];
		$this->__keywords = $params["keywords"];
		$this->__description = $params["description"];
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

	public function getRootUrl()
	{
		return "http" . $_SERVER['HTTP_HOST'];
	}

}
