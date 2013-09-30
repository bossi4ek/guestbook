<?php

class Response {
    public $data = array();

    public function __construct($name_data, $data, $template)
    {
        $this->setData($name_data, $data);
        include $_SERVER['DOCUMENT_ROOT']."/templates/".$template;
    }

    public function setData($name, $data)
    {
        $this->data[$name] = $data;
    }
    public function getData()
    {
        return $this->data;
    }
}