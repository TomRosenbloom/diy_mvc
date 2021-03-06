<?php

/**
 * for controllers of domain models i.e. anything but static pages, I reckon
 */
abstract class DomainModelController extends BaseController
{
    private $modelName;
    protected $model;
    
    /**
     * the model name is set in the extending controller
     * 
     * @param type $modelName
     */
    function __construct($modelName) {
        $this->setModelName($modelName);
        $this->model = new $this->modelName; // should I use setter or not?? something about immutability...
    }
    
    function getModelName() {
        return $this->modelName;
    }

    function getModel() {
        return $this->model;
    }

    function setModelName($modelName) {
        $this->modelName = $modelName;
    }

    function setModel($model) {
        $this->model = $model;
    }
    
}

