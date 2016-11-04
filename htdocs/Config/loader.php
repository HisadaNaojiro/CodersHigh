<?php

require_once('siteSetting.php');
require_once('view.php');
require_once('config.php');
require_once('database.php');
require_once('response.php');
require_once('request.php');
require_once('session.php');

class Loader extends Config
{

  public $View = null;
  public $SiteSetting = null;
  public $Request = null;
  public $Session = null;
  public $Response = null;

  public function __construct()
  {
    $this->View = $this->setInstance('View');
    $this->SiteSetting = $this->setInstance('SiteSetting');
    $this->Request = $this->setInstance('Request');
    $this->Session = $this->setInstance('Session');
    $this->Response = $this->setInstance('Response');
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
