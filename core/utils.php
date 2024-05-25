<?php
function readJSON($file)
{
    $filecontent = file_get_contents($file);
    $data        = json_decode($filecontent, true);
    return $data;
}
/* 
Serializes an object to a JSON string 
*/
function createJson($json){
    return json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function save($filepath, $json){
    $fp = fopen($filepath, 'w');
    fwrite($fp, createJson($json));
    fclose($fp);
}

function update($filepath, $json){
    if(!file_exists($filepath)) return save($filepath, $json);
    $cnts = readJSON($filepath);
    if(!$cnts) return;
    if(is_array($cnts)){
        array_push($cnts, $json);
    }
    save($filepath, $cnts);
}


function redirectTo ($url) {
	//header("Location: ".$url);
}

function destroySession () {
    session_unset();
    session_destroy();
}


function sanitizeString($data)
{
    return strip_tags((trim($data)));
}

function debug($stuff)
{
    echo '<pre style="background:white; padding:20px; font-weight:bold;">' . print_r($stuff, true) . '</pre>';
}
