<?php

class HyperRequest {
    const JSONRESPONSE = 'Content-Type: application/json';
    const TEXTRESPONSE = 'Content-Type: text/html';
    public $scheme;
    public $domain;
    public $url;
    public $path;
    public $params;
    public $method; 
    public $contentType; 
    public $port; 
    public $ctx; 
    public $payload; 
    public $response; 

    public function __construct()
    {
        $this->scheme = $_SERVER['REQUEST_SCHEME'];
        $this->domain = $_SERVER['HTTP_HOST'];
        $this->path = filter_var(explode('?', $_SERVER['REQUEST_URI'],2)[0], FILTER_SANITIZE_URL);
        $this->url = $this->scheme . "://" . $this->domain . $this->path;
        $this->params = $_GET;
        $this->port = $_SERVER['SERVER_PORT'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->contentType = $_SERVER['CONTENT_TYPE'];
        $this->payload = $this->getJSON(true);
    }

    public function session(){
        return isset($_SESSION['username']) ? true : false;
    }

    public static function setHeader(String $contentType){
        header($contentType);
    }

    public function isAPI(){
        $needle = '/api';
        return strncmp($this->path, $needle, strlen($needle)) === 0;
    }
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public static function isPost()
    {
        return isset($_SERVER['POST']);
    }

    public static function getJSON($with_schema=false)
    {
        $json = json_decode(file_get_contents('php://input'), true);
        if($with_schema && $json){
            $schema = $json['@schema'];
            unset($json['@schema']);
            return [$schema, $json];
        }
        return $json;
    }

    public function isAsset($site){
        return !$site->assetsURI ? false: str_contains($this->path, $site->assetsURI);
    }

    public function resolve($site){

        list($tmpl, $ctx) = !($this->payload) ? $site->getContext() : [false, $this->payload];

        if($this->isAPI()){
            $this->setHeader(HyperRequest::JSONRESPONSE);
            if(!$tmpl){
                return print_r(json_encode($site->saveToFS($ctx) , JSON_PRETTY_PRINT));
            }
            return print_r(json_encode($ctx,JSON_PRETTY_PRINT));
        }
        // $site->ctx = $ctx;
        $site->renderHtml($tmpl, $ctx);
    }
}