<?php

require_once("../../sys/Config.php");
require_once("../../sys/DB.class.php");
require_once("../../sys/DAO.class.php");
require_once("../../sys/Modal.class.php");

require_once("../../apis/funcoes/Funcoes.class.php");

require_once ("../../models/Customers.class.php");

//Cabecalho api Slim Framework inicio
require_once ("../../apis/Slim/Slim.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');
//Cabecalho api Slim Framework fim

$app->get('/', function () { //protecao da pasta controllers/webservice
  	echo "ACESSO RESTRITO";
});

//metodos de chamadas da api Slim Framework inicio

$app->get('/testes', 'testes');

$app->get('/customers', 'customers');
$app->get('/customer/:param', 'customer');
$app->post('/insertCustomer', 'insertCustomer');
$app->post('/updateCustomer', 'updateCustomer');
$app->delete('/deleteCustomer/:param', 'deleteCustomer');


//metodos de chamadas da apei Slim Framework fim
$app->run();// start api

//testes inicio
function testes(){

	echo "Testando!";

}
//testes fim

//customers inicio
function customers(){
	$customers = new Customers();
	$customers->campos = 'customerNumber, customerName, email, address, city, state, postalCode, country';
	$customers->extras_select = 'ORDER BY customerNumber DESC';
	$result = $customers->findAll($customers);
	echo json_encode($result);
}

function customer($param){
	$customer = new Customers();
	$customer->campos = 'customerNumber, customerName, email, address, city, state, postalCode, country';
	$customer->extras_select = 'WHERE customerNumber="'.$param.'"';
	$result = $customer->findAll($customer);
	echo Funcoes::retiraCaracteres(json_encode($result));
}

function insertCustomer(){
	$request = \Slim\Slim::getInstance()->request();
	$dados = json_decode($request->getBody(),true);
	
	$customerName = $dados['customerName'];
	$email = $dados['email'];
	$address = $dados['address'];
	$city = $dados['city'];
	$state = $dados['state'];
	$postalCode = $dados['postalCode'];
	$country = $dados['country'];
	
	$customer = new Customers();
	$customer->setValor('customerName',$customerName);
	$customer->setValor('email',$email);
	$customer->setValor('address',$address);
	$customer->setValor('city',$city);
	$customer->setValor('state',$state);
	$customer->setValor('postalCode',$postalCode);
	$customer->setValor('country',$country);
	$result = $customer->insert($customer);
	if($result>0){
		$success = array('status' => "Success", "msg" => "Cliente criado com sucesso.", "data" => $dados);
	}else{
		$success = array('status' => "Failure", "msg" => "Erro ao cadastrar. Tente Novamente!.");
	}
	echo json_encode($success);
}

function updateCustomer(){
	$request = \Slim\Slim::getInstance()->request();
	$dados = json_decode($request->getBody(),true);
	
	$customerNumber = $dados['customer']['customerNumber'];
	$customerName = $dados['customer']['customerName'];
	$email = $dados['customer']['email'];
	$address = $dados['customer']['address'];
	$city = $dados['customer']['city'];
	$state = $dados['customer']['state'];
	$postalCode = $dados['customer']['postalCode'];
	$country = $dados['customer']['country'];
	
	$customer = new Customers();
	$customer->valorpk = $customerNumber;
	$customer->setValor('customerName',$customerName);
	$customer->setValor('email',$email);
	$customer->setValor('address',$address);
	$customer->setValor('city',$city);
	$customer->setValor('state',$state);
	$customer->setValor('postalCode',$postalCode);
	$customer->setValor('country',$country);
	$result = $customer->update($customer);
	if($result>0){
		$success = array('status' => "Success", "msg" => "Customer ".$customerNumber." Updated Successfully.", "data" => $dados);
	}else{
		$success = array('status' => "Failure", "msg" => "Erro ao alterar. Tente Novamente!.");
	}
	echo json_encode($success);
}

function deleteCustomer($param){
	$customer = new Customers();
	$customer->valorpk = $param;
	$result = $customer->delete($customer);
	if($result>0){
		$success = array('status' => "Success", "msg" => "Successfully deleted one record.");
	}else{
		$success = array('status' => "Failure", "msg" => "Erro ao excluir. Tente Novamente!.");
	}
	echo json_encode($success);
}
//customers fim
