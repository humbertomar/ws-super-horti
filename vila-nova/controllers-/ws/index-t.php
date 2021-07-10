<?php

///WS

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

//require_once("../../sys/Config.php");
require_once("../../sys/DB.class.php");
require_once("../../sys/DAO.class.php");
require_once("../../sys/Modal.class.php");

//Cabecalho api Slim Framework inicio
require_once ("../../apis/Slim/Slim.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');
//Cabecalho api Slim Framework fim

$app->get('/', function () {
  	echo '{"message":"ACESSO RESTRITO"}';
});

//metodos de chamadas da api Slim Framework inicio

$app->get('/testes', 'testes');

$app->post('/testesPOST', 'testesPOST');

$app->post('/login', 'login');

//metodos de chamadas da apei Slim Framework fim
$app->run();// start api

function testes(){
    
    echo "testet";
    
}

function testesPOST(){
    
	$request = \Slim\Slim::getInstance()->request();
	$array = json_decode($request->getBody(),true);
	
	echo $request->getBody();
    
}

function login() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    echo '{"msg":"'.$request->getBody().'"}';

}

function salvaTXT($param){
	$name = 'arquivo.txt';
	$text = $param;
	$file = fopen($name, 'a');
	fwrite($file, $text);
	fclose($file);
}