<?php

namespace App;

use Illuminate\Foundation\Application as IlluminateApplication;

class Application extends IlluminateApplication
{
    protected $appPath = __DIR__;
    protected $store_id;


    public function __construct($basePath = null)
    {
        if(!$this->store_id) $this->setStore(1);
        parent::__construct($basePath);
    }

    public function setStore($id)
    {
    	$this->store_id = $id;
    	return $this;
    }

    public function getStore()
    {
        return $this->store_id;
    }

}
