<?php

require_once('siteSetting.php');
require_once('view.php');
require_once('config.php');
require_once('database.php');

class Loader extends Config
{

  public $View = null;
  public $SiteSetting = null;

  public function __construct()
  {
    $this->View = $this->setInstance('View');

    $this->SiteSetting = $this->setInstance('SiteSetting');
    $this->setInstance('Database');
  }

  public function setInstance($class,$value = null)
  {
    if(!empty($value)){
      return new $class($value);
    }
    return new $class;
  }



}
