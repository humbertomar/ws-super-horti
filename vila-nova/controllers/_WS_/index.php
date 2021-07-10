<?php

///WS

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

require_once("../../sys/Config.php");

require_once("../../sys/DB.class.php");
require_once("../../sys/DAO.class.php");
require_once("../../sys/Modal.class.php");


require_once ("../../models/Settings.class.php");

require_once ("../../models/Products.class.php");

require_once ("../../models/Clientes.class.php");

require_once ("../../models/Pedidos.class.php");

require_once ("../../models/Products_.class.php");

//Cabecalho api Slim Framework inicio
require_once ("../APIS/Slim/Slim.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');
//Cabecalho api Slim Framework fim

$app->get('/', function () {
  	echo '{"message":"ACESSO RESTRITO"}';
});

//metodos de chamadas da api Slim Framework inicio

$app->get('/testes', 'testes');
$app->get('/teste/:param', 'teste');

$app->post('/testesPOST', 'testesPOST');

////////////////////////////////////////////////////

$app->get('/tickets', 'tickets');

$app->get('/analisar', 'analisar');

$app->get('/msgsApp/:param', 'msgsApp');

$app->get('/settings', 'settings');

$app->get('/pedidosDoDia/:param', 'pedidosDoDia');
$app->get('/pedidosUserID/:param', 'pedidosUserID');
$app->get('/pedidosAgendadosUserID/:param', 'pedidosAgendadosUserID');
$app->get('/pedidoID/:param', 'pedidoID');

$app->get('/pedidosAgendados/:param', 'pedidosAgendados');

$app->get('/googleMaps/:origins', 'googleMaps');
$app->get('/googleMaps2/:origins', 'googleMaps2');
$app->get('/getGeoCode/:param', 'getGeoCode');

$app->get('/apiCorreios/:param', 'apiCorreios');

$app->get('/offers','offers');

$app->get('/products', 'products');
$app->get('/product/:param', 'product');
$app->get('/productID/:param', 'productID');

$app->get('/products_', 'products_');

$app->get('/promotions', 'promotions');

$app->get('/category', 'category');

$app->get('/sliders', 'sliders');

$app->get('/clientes', 'clientes');

$app->get('/listarpedidos', 'listarpedidos');
$app->get('/listarpedidosdiacorrente', 'listarpedidosdiacorrente');
$app->get('/listarpedidosdiacorrenteData/:param', 'listarpedidosdiacorrenteData');

$app->get('/listarpedidosagendadosData/:param', 'listarpedidosagendadosData');
$app->get('/listarpedidosagendadosData2/:param', 'listarpedidosagendadosData2');

$app->get('/listarpedidodiacorrente/:param', 'listarpedidodiacorrente');

$app->get('/listarpedidospagos','listarpedidospagos');

$app->get('/itenspedido/:param', 'itenspedido');

$app->post('/pedidos', 'pedidos');

$app->post('/cancelPedido', 'cancelPedido');

$app->post('/relatorioProdutosDoDia', 'relatorioProdutosDoDia');

$app->post('/editPedidosAgendadosUserID', 'editPedidosAgendadosUserID');

//metodos de chamadas da apei Slim Framework fim
$app->run();// start api

function apiCorreios($param){
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Accept: application/json\r\n"
        ]
    ];
    
    $context = stream_context_create($opts);
    
    $file = file_get_contents('http://cep.la/'.$param, true, $context);
    
    echo '['.$file.']';
}

function tickets(){
    
    $pedidos = new Pedidos();
    $pedidos->campos = 'pedido_id, log_pedidos';
    
    $pedidos->extras_select = 'WHERE status = 1 AND	ticket = 1 ORDER BY pedido_id ASC';
    
    //$pedidos->extras_select = 'WHERE pedido_id = 442 AND status = 1 AND	ticket = 1 ORDER BY pedido_id ASC';
    $pedidos = $pedidos->findAll($pedidos);
    
    $pedidosJson = json_encode($pedidos);
    
    $pedidosArray = json_decode($pedidosJson,true);
    
    $size1 = count($pedidosArray);
    
    for ($j = 0; $j < $size1; $j++) {
        
        if(json_validate($pedidosArray[$j]['log_pedidos'])>0){
            /*echo $pedidosArray[$j]['pedido_id'] . " | " . json_validate($pedidosArray[$j]['log_pedidos']);
            echo " | ";*/
            
            //echo $pedidosArray[$j]['log_pedidos'];
            
            $array = json_decode($pedidosArray[$j]['log_pedidos'],true);
            
            //print_r($array);
            
            //print_r($array['cart']);
                
        	    $size = count($array['cart']);
        	    
        	    //echo $size." | ";
            
                for ($i = 0; $i < $size; $i++) {
                    
                    if($array['cart'][$i]['itemTotalPrice'] != 0){
                        
                        //echo $array['cart'][$i]['itemTotalPrice'];
                        //echo " | ";
                        
                        $pedidoitem = new PedidoItems2();
                	    $pedidoitem->setValor('pedido_id',$pedidosArray[$j]['pedido_id']);
                	    $pedidoitem->setValor('orderId',$array['orderId']);
                        $pedidoitem->setValor('itemId',$array['cart'][$i]['item']['itemId']);
                        $pedidoitem->setValor('name',strip_tags(utf8_encode($array['cart'][$i]['item']['name'])));
                        $pedidoitem->setValor('price',$array['cart'][$i]['item']['price']);
                        $pedidoitem->setValor('image',$array['cart'][$i]['item']['image']);
                        $pedidoitem->setValor('itemQunatity',$array['cart'][$i]['item']['itemQunatity']);
                        $pedidoitem->setValor('itemTotalPrice',$array['cart'][$i]['item']['itemQunatity']*$array['cart'][$i]['item']['price']);
                        
                        $pedidoitem->setValor('pedido_id_r',$pedidosArray[$j]['pedido_id'].'i'.$i);
                        
                        $result2 = $pedidoitem->insert($pedidoitem);
                	    
                	    /*if($result2>0){
                	        //$success = array('status' => "Success", "msg" => "Pedido criado com sucesso!", "data" => $array);
                	        $success = 1;
                	    }else{
                		    //$success = array('status' => "Failure", "msg" => "Erro ao cadastrar. Tente Novamente!.");
                		    $success = 0;
                	    }*/
                	    
                	    /*if($result2 == 0){
                	        echo $pedidosArray[$i]['pedido_id'];
                	        
                	        $pedidos = new DeletePedidoItems2();
                        	$pedidos->valorpk = $pedidosArray[$j]['pedido_id'];
                        	$pedidos->setValor('status',0);
                        	$pedidos->update($pedidos);
                        	
                	        break;
                	    }*/
    
                	    if($i == ($size-1)){
                	        $ticket = new TicketPedido();
                        	$ticket->valorpk = $pedidosArray[$j]['pedido_id'];
                        	$ticket->setValor('ticket',0);
                        	$ticket->update($ticket);
                	    }
                	    
                	    //echo $result2;
                	    //echo " | ";
                	    
                	    //echo $pedidosArray[0]['pedido_id'];
                	    //echo " | ";
            	    
                    }
                
            	    
            	    
                }
                
                echo $pedidosArray[$j]['pedido_id'];
                echo " | ";
                
                //echo $pedidosArray[0]['pedido_id'];
            	//echo " | ";
        	
        	//$success = array('status' => "OK", "msg" => "Tudo certo!");
        	//echo json_encode($success);
        	//echo 1;
        }
        
    }
    
}

function getGeoCode($param){
    
    $key = 'AIzaSyAjayuD_XLSuXptvzHPfsAt2OBEFDC4lOE';
    
    $a = $param;
    //$a = "Rua do Café, sn, solar viller, condomínio bosque dos buritis, Goiânia - GO"; // Pega parâmetro
    $addr = str_replace(" ", "+", $a); // Substitui os espaços por + "Rua+Paulo+Guimarães,+São+Paulo+-+SP" conforme padrão 'maps.google.com'
    $address = utf8_encode($addr);

    $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.$key.'&address='.$address.'&sensor=false');
    
    $output= json_decode($geocode);
    
    $lat = $output->results[0]->geometry->location->lat;
    $long = $output->results[0]->geometry->location->lng;
    
    echo '{"latitude":"'.$lat.'","longitude":"'.$long.'"}';
    
}

//testes inicio
function testesPOST(){
    
	$request = \Slim\Slim::getInstance()->request();
	$array = json_decode($request->getBody(),true);
	
	echo $request->getBody();
	
	//salvaTXT($request->getBody());
	
	///$array = explode('=', $request->getBody());
	
	///echo json_encode($array[1]);
	
    //echo json_encode($array);
    
}

function cancelPedido(){
	$request = \Slim\Slim::getInstance()->request();
	
	date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d H:i:s', time());//2020-06-08 10:50:16
	
	$idPedido = json_decode($request->getBody(),true);
	
	$pedido = new CancelPedido();
	$pedido->valorpk = $idPedido;
	$pedido->setValor('data_cancel_entrega',$dataLocal);
	$pedido->setValor('status_pedido',0);
	$result = $pedido->update($pedido);
	if($result>0){
		$success = array('status' => "Success", "msg" => "Peddo cancelado com sucesso!.");
	}else{
		$success = array('status' => "Failure", "msg" => "Erro ao cancelar pedido. Tente Novamente!.");
	}
	echo json_encode($success);
}

function analisar(){
    
    date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    
    //echo $dataLocal;
    
    //echo 'TESTE';
    
    $pedidos = new Pedidos();
    $pedidos->campos = 'pedido_id';
    //$pedidos->extras_select = 'WHERE status_pedido = 1 AND status = 1 ORDER BY pedido_id ASC';
    $pedidos->extras_select = 'WHERE status_pedido = 1 AND status = 1 AND data_entrega >= "'.$dataLocal.'" ORDER BY pedido_id ASC';
    $pedidos = $pedidos->findAll($pedidos);
    
    $pedidosJson = json_encode($pedidos);
    
    $pedidosArray = json_decode($pedidosJson,true);
    
    $size = count($pedidosArray);
    
    //echo $size.' | ';
    //print_r($pedidosArray);
    
    for ($i = 0; $i < $size; $i++) {
        //echo $pedidosArray[$i]['pedido_id'].' | ';
        testar($pedidosArray[$i]['pedido_id']);
        //aqui acha os pedidos com itens diferentes
    }
    
    //testar(404);
    ///testar(156);
    
}

function testar($pedido_id){
    
    //$pedido_id = 386;
    
    //echo $pedido_id;
    
    $pedidos = new Pedidos();
    $pedidos->campos = 'log_pedidos';
    $pedidos->extras_select = 'WHERE pedido_id = '.$pedido_id;
    $pedidos = $pedidos->findAll($pedidos);
    
    //print_r($pedidos);
    
    $pedidosJson = json_encode($pedidos);
	$pedidosArray = json_decode($pedidosJson,true);
	
	$array = json_decode($pedidosArray[0]['log_pedidos'],true);
	//print_r($array['cart']);
	
	$sizeLogPedidos = count($array['cart']);
	
	//echo $sizeLogPedidos;
    //echo " | ";
    
    $pedidoitem = new PedidoItems();
    //$pedidoitem->campos = 'pedido_id,orderId,itemId';
    $pedidoitem->campos = 'orderId';
    $pedidoitem->extras_select = 'WHERE pedido_id = '.$pedido_id;
	$pedidoitem = $pedidoitem->findAll($pedidoitem);
	
	//print_r($pedidoitem);
	
	$pedidoitemArray = json_decode(json_encode($pedidoitem),true);
	//print_r($pedidoitemArray);
	
	$sizePedidoItens = count($pedidoitem);
	//echo $sizePedidoItens;
	
	//itens no log de pedidos
	/*for ($i = 0; $i < $sizeLogPedidos; $i++) {
	    if($array['cart'][$i]['item']['itemId'] > 0){
	        echo $array['cart'][$i]['item']['itemId'];
	        echo " | ";
	    }
	}*/
	
	//echo $sizeLogPedidos. '->' .$sizePedidoItens;
	//echo " | ";
	
	if($sizeLogPedidos != $sizePedidoItens){
	    
	    //echo json_encode($pedidoitem);
	    echo $pedido_id;
	    echo ' | ';
	    echo $sizeLogPedidos. '->' .$sizePedidoItens;
	    echo " | ";
	    
	    /*$delitem2 = new DeletePedidoItems2();
	    $delitem2->valorpk = $pedido_id;
        $delitem2->setValor('status',0);
        $result = $delitem2->update($delitem2);
        	
        echo $result;
        echo " | ";*/
	    
	    ///for ($i = 0; $i < $sizeLogPedidos; $i++) {
	        
	        /*$delitem2 = new DeletePedidoItems2();
	        $delitem2->valorpk = $pedido_id;
        	$delitem2->setValor('status',0);
        	$result = $delitem2->update($delitem2);
        	
        	echo $result;
        	echo " | ";*/
	        
	        //echo $array['cart'][$i]['itemTotalPrice'];
	        //echo " | ";
	        
	        /*if($array['cart'][$i]['itemTotalPrice'] > 0){
        
        	    $pedidoitem = new PedidoItems2();
        	    
        	    $pedidoitem->setValor('pedido_id_r',$pedido_id.'i'.$i);
        	    
        	    $pedidoitem->setValor('pedido_id',$pedido_id);
        	    $pedidoitem->setValor('orderId',$array['orderId']);
                $pedidoitem->setValor('itemId',$array['cart'][$i]['item']['itemId']);
                $pedidoitem->setValor('name',strip_tags(utf8_encode($array['cart'][$i]['item']['name'])));
                $pedidoitem->setValor('price',$array['cart'][$i]['item']['price']);
                $pedidoitem->setValor('image',$array['cart'][$i]['item']['image']);
                $pedidoitem->setValor('itemQunatity',$array['cart'][$i]['item']['itemQunatity']);
                $pedidoitem->setValor('itemTotalPrice',$array['cart'][$i]['item']['itemQunatity']*$array['cart'][$i]['item']['price']);
                
                //$pedidoitem->setValor('pedido_id_r',$pedido_id+$i);
                
                $result2 = $pedidoitem->insert($pedidoitem);
        	    if($result2>0){
        	        $success = array('status' => "Success", "msg" => "Pedido criado com sucesso!", "data" => $array);
        	    }else{
        		    $success = array('status' => "Failure", "msg" => "Erro ao cadastrar. Tente Novamente!.");
        	    }
    	    
	        }*/
    	    
        ///}
	    
	}
	
}

function testes(){
    
    $origins = '74685-190';
    
    if(isset($origins) AND !empty($origins)){
		//$origins = '74080-240';//casa
		$destinations = '74675-090';//CEASA - Goiânia - GO.
		$mode = 'CAR';
		$language = 'PT';
		$sensor = false;
		
		$key = 'AIzaSyAjayuD_XLSuXptvzHPfsAt2OBEFDC4lOE';
		
		$resultado =  file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$origins.'&destinations='.$destinations.'&mode='.$mode.'&language='.$language.'&sensor=false&key='.$key);
		
		$resultado2 =  file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$origins2.'&destinations='.$destinations.'&mode='.$mode.'&language='.$language.'&sensor=false&key='.$key);
		
		$dados = json_decode($resultado,true);
		
		echo $resultado;
		
		//$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
		//echo $array[0];
		//print_r($array);
		//echo(count($array));
		
		//print_r($dados);
		
		if($dados['status'] == 'OK'){
		
			//echo json_encode(($dados['rows'][0]['elements'][0]['distance']['text']));
			$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
			
			//echo $array[0];
			
			if(isset($array[0]) AND !empty($array[0])){
			
				if($array[1] == "km"){
					if($array[0] <= 5){
						echo json_encode(0.00);
					}else if($array[0] <= 10){
						echo json_encode(15.00);
					}else if($array[0] <= 25){
						echo json_encode(15.00);
					}else if($array[0] <= 30){
						echo json_encode(25.00);
					}else{
						echo json_encode("Não fazemos entrega nessa região!");
					}
				}else{
					echo json_encode(0.00);
				}
			
			}else{
				//echo json_encode('Digite um CEP válido');
				echo json_encode("Não fazemos entrega nessa região!");
				//echo json_encode('CEP inválido ou não fazemos entrega nessa região! Tente novamente, ou tente outro endereço para entrega!');
			}
		
		}else{
			//echo json_encode('Erro ao acessar o sistema, tente mais tarde!');
			echo json_encode('Digite um CEP válido');
		}
	}else{
		echo json_encode('Digite um CEP válido');
	}
	
    /*date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    
    //echo $dataLocal;
    
    //echo 'TESTE';
    
    $pedidos = new Pedidos();
    $pedidos->campos = 'pedido_id';
    $pedidos->extras_select = 'WHERE status_pedido = 1 AND status = 1 ORDER BY pedido_id ASC';
    //$pedidos->extras_select = 'WHERE status_pedido = 1 AND status = 1 AND data_entrega >= "'.$dataLocal.'" ORDER BY pedido_id ASC';
    $pedidos = $pedidos->findAll($pedidos);
    
    $pedidosJson = json_encode($pedidos);
    
    $pedidosArray = json_decode($pedidosJson,true);
    
    $size = count($pedidosArray);
    
    //echo $size.' | ';
    //print_r($pedidosArray);
    
    for ($i = 0; $i < $size; $i++) {
        //echo $pedidosArray[$i]['pedido_id'].' | ';
        testar($pedidosArray[$i]['pedido_id']);
        //aqui acha os pedidos com itens diferentes
    }*/
    
    //testar(404);
    ///testar(156);
    
    
    /*	    $pedidoitem = new PedidoItems();
    	    $pedidoitem->setValor('pedido_id',$result);
    	    $pedidoitem->setValor('orderId',$array['orderId']);
            $pedidoitem->setValor('itemId',$array['cart'][$i]['item']['itemId']);
            $pedidoitem->setValor('name',strip_tags(utf8_encode($array['cart'][$i]['item']['name'])));
            $pedidoitem->setValor('price',$array['cart'][$i]['item']['price']);
            $pedidoitem->setValor('image',$array['cart'][$i]['item']['image']);
            $pedidoitem->setValor('itemQunatity',$array['cart'][$i]['item']['itemQunatity']);
            $pedidoitem->setValor('itemTotalPrice',$array['cart'][$i]['item']['itemQunatity']*$array['cart'][$i]['item']['price']);
            $result2 = $pedidoitem->insert($pedidoitem);*/
   
    //}
    
    /*
    $origins = '74080-240';//casa
    
    $origins2 = preg_replace('/[^0-9]/', '', $origins);
    
    //echo $origins2;
    
    if(isset($origins) AND !empty($origins)){
		//$origins = '74080-240';//casa
		$destinations = '74675-090';//CEASA - Goiânia - GO.
		$mode = 'CAR';
		$language = 'PT';
		$sensor = false;
		
		$key = 'AIzaSyAjayuD_XLSuXptvzHPfsAt2OBEFDC4lOE';
		
		$resultado =  file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$origins.'&destinations='.$destinations.'&mode='.$mode.'&language='.$language.'&sensor=false&key='.$key);
		
		$dados = json_decode($resultado,true);
		
		print_r($dados['origin_addresses'][0]);
		
		//$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
		//echo $array[0];
		//print_r($array);
		//echo(count($array));
		
		//print_r($dados);
		
		if($dados['status'] == 'OK'){
		
			//echo json_encode(($dados['rows'][0]['elements'][0]['distance']['text']));
			$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
			
			//echo $array[0];
			
			if(isset($array[0]) AND !empty($array[0])){
			
				if($array[1] == "km"){
					if($array[0] <= 5){
						echo json_encode(0.00);
					}else if($array[0] <= 10){
						echo json_encode(15.00);
					}else if($array[0] <= 25){
						echo json_encode(15.00);
					}else if($array[0] <= 30){
						echo json_encode(25.00);
					}else{
						echo json_encode("Não fazemos entrega nessa região!");
					}
				}else{
					echo json_encode(0.00);
				}
			
			}else{
				//echo json_encode('Digite um CEP válido');
				echo json_encode("Não fazemos entrega nessa região!");
				//echo json_encode('CEP inválido ou não fazemos entrega nessa região! Tente novamente, ou tente outro endereço para entrega!');
			}
		
		}else{
			echo json_encode("ERROR!");
		}
	}else{
		echo json_encode('Digite um CEP válido');
	}
	*/
    
}

function teste($param){
	echo '{"msg":"'.$param.'"}';
}
//testes fim

function settings(){
    
    $settings = new Settings();
    //$settings->campos = 'bloquear_app,qtdade_pedidos_dia,horario_maximo_pedidos';
	$settings = $settings->findAll($settings);
	echo json_encode($settings);
    
}

function relatorioProdutosDoDia(){
	
	$request = \Slim\Slim::getInstance()->request();
	//$array = json_decode($request->getBody(),true);
	$data = explode('=', $request->getBody());
	$param = $data[1];
	//echo $param;
    $pedidos = new Pedidos();
	//$pedidos->campos = 'pedidos.pedido_id,	pedidos.orderId, email, pedidos.name AS cliente, pedido_items.name AS produto, itemId, image, itemQunatity, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, count(*) as total';
	$pedidos->campos = 'pedido_items.name AS produto, itemId, image, count(*) as total, SUM(itemQunatity) as itemQunatitySoma';
	//$pedidos->campos = 'pedidos.pedido_id, itemId, itemQunatity, createdAt, status_pagamento, status_pedido, count(*) as total';
	//$pedidos->extras_select = 'INNER JOIN pedido_items ON pedidos.pedido_id = pedido_items.pedido_id WHERE DATE(createdAt) = "2020-06-08" GROUP BY itemId ORDER BY createdAt DESC';
	$pedidos->extras_select = 'INNER JOIN pedido_items ON pedidos.pedido_id = pedido_items.pedido_id WHERE DATE(createdAt) = "'.$param.'" GROUP BY itemId ORDER BY data_entrega ASC';
	$pedidos = $pedidos->findAll($pedidos);
	$pedidosJson = json_encode($pedidos);
	$pedidosArray = json_decode($pedidosJson,true);
	
	//print_r($pedidosArray);
	
	echo $pedidosJson;
}

function pedidosDoDia($param){
    
    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Sao_Paulo');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    //$dataLocal = date('Y-m-d H:i:s', time());//2020-06-08 10:50:16
    ///$dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    
    $dataLocal = $param;
    
    //echo $dataLocal;
    
    $pedidos = new Pedidos();
	$pedidos->extras_select = 'WHERE DATE(data_entrega) = "'.$dataLocal."%".'" AND status != 0 ORDER BY data_entrega ASC';
	//$pedidos->extras_select = 'ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	///echo json_encode($pedidos);
	echo count($pedidos);
    
}

function pedidosUserID($param){
    $pedidos = new Pedidos();
    $pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax , tipo_pagamento, troco, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, data_entrega';
	$pedidos->extras_select = 'WHERE pedido_agendado = 0 AND userid = "'.$param.'" AND status_pedido != 0 AND status != 0 ORDER BY data_entrega ASC';
	$pedidos = $pedidos->findAll($pedidos);
	echo json_encode($pedidos);
	
	//print_r($pedidos);
}

function pedidosAgendadosUserID($param){
    $pedidos = new Pedidos();
    $pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax , tipo_pagamento, troco, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, data_entrega, data_cancel_entrega';
	//$pedidos->extras_select = 'WHERE userid = "'.$param.'" AND pedido_agendado = 1 AND status_pedido != 0 AND status != 0 ORDER BY data_entrega ASC';
	
	$pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax , tipo_pagamento, troco, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, data_entrega, data_cancel_entrega';
	$pedidos->extras_select = 'WHERE userid = "'.$param.'" AND pedido_agendado = 1 AND status != 0 ORDER BY data_entrega ASC';
	
	
	//$pedidos->extras_select = 'WHERE DATE_FORMAT(createdAt, "%Y-%m-%d") != DATE_FORMAT(data_entrega, "%Y-%m-%d") AND userid = "'.$param.'" AND (data_entrega != 0000-00-00 OR data_entrega != "") AND status_pedido = 1 ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	echo json_encode($pedidos);
}

function pedidosAgendados($param){
    $pedidos = new Pedidos();
    //$pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax , tipo_pagamento, troco, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, data_entrega, data_cancel_entrega';
	
	$pedidos->extras_select = 'WHERE pedido_agendado = 1 AND status_pedido != 0 AND status != 0 ORDER BY data_entrega ASC';
	
	/*$pedidos->extras_select = 'WHERE DATE(data_entrega) = "'.$param."%".'" ORDER BY data_entrega ASC';*/
	
	$pedidos = $pedidos->findAll($pedidos);
	//echo json_encode($pedidos);
	
	if(count($pedidos) > 0){
	    $return_arr = array();
    	foreach ($pedidos as $row) {
    		//print_r($valor);
    		//echo $row->id;
    		
    		$datahora = new DateTime($row->createdAt);
    		
    		if($datahora->format('Y-m-d') != $row->data_entrega){
    	        //echo "Diferente";
    	        
    	        $data_entrega = new DateTime($row->data_entrega);
    	        
    	        switch ($row->tipo_pagamento) {
                    case "cartao_credito":
                       $tipoPagamento = "Cartão de Crédito";
                       break;
                    case "cartao_debito":
                       $tipoPagamento = "Cartão de Débito";
                       break;
                    case "transferencia_bancaria":
                       $tipoPagamento = "Tranferência Bacária";
                       break;
                    case "dinheiro":
                        $tipoPagamento = "Dinheiro";
                        break;
                }
                
                switch ($row->status_pagamento) {
                   case 1:
                       $statusPagamento = "<span class='label label-warning'>Pendente</span>";
                       break;
                   case 2:
                       $statusPagamento = "<span class='label label-success'>Pago</span>";
                       break;
                   case 3:
                       $statusPagamento = "<span class='label label-danger'>Cancelado</span>";
                       break;
                }
                
                switch ($row->status_pedido) {
                   case 1:
                       $statusPedido = "<span class='label label-warning'>Pendente</span>";
                       break;
                   case 2:
                       $statusPedido = "<span class='label label-success'>Entregue</span>";
                       break;
                   case 0:
                       $statusPedido = "<span class='label label-danger'>Cancelado</span>";
                       break;
                }
        		
        		$slid = array();
				//$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/orders/printDiv/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a>';
        		$slid['acao'] = '<a target="__blank" href="https://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="https://superhorti.com.br/appAdmin/update/pedido.php?id='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
        		//$slid['pedido_id'] = $row->pedido_id;
        		$slid['orderId'] = $row->orderId;
        		$slid['name'] = strip_tags(utf8_encode($row->name));
        		$slid['data_entrega'] = $data_entrega->format('d/m/Y');
        		$slid['grandTotal'] = $row->grandTotal;
        		$slid['tipo_pagamento'] = $tipoPagamento;
        		$slid['status_pagamento'] = $statusPagamento;
        		$slid['status_pedido'] = $statusPedido;
        		//$slid['status_pagamento'] = $row->status_pagamento;
        		//$slid['status_pedido'] = $row->status_pedido;
        		
        		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
        		array_push($return_arr,$slid);
        	}
        	//echo '{"data": '.json_encode($return_arr).'}';
        	//print_r($return_arr);
        	
        	foreach ($return_arr as $key => $value) {
        	    $result['data'][$key] = array(
					$value['acao'],
        			//$value['pedido_id'],
        			$value['orderId'],
        			$value['name'],
        			$value['data_entrega'],
        			$value['grandTotal'],
        			$value['tipo_pagamento'],
        			$value['status_pagamento'],
        			$value['status_pedido']
        		);
    	    }
    		
    	}
    	
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
}

function editPedidosAgendadosUserID(){
    $request = \Slim\Slim::getInstance()->request();
	$array = json_decode($request->getBody(),true);
	
	//salvaTXT($request->getBody());
	//salvaTXT($array['dataPedidoAgendado']);
	//
	///echo $request->getBody();
	
	//cadastra o cliente antes
	$cliente = new Clientes();
    $cliente->setValor('name',strip_tags(utf8_encode($array['userDetails']['name'])));
    $cliente->setValor('email',$array['userDetails']['email']);
    $cliente->setValor('mobileNo',$array['userDetails']['mobileNo']);
    $cliente->setValor('userId',$array['userDetails']['userid']);
    $cliente->insert($cliente);
    
    $shippingAddress = (isset($array['shippingAddress'])) ? json_encode($array['shippingAddress']) : NULL;
	
	$observacoes = (isset($array['observacoes'])) ? $array['observacoes'] : NULL;
	
	$data_entrega = (isset($array['dataPedidoAgendado'])) ? $array['dataPedidoAgendado'] : NULL;
	
	$pedido = new Pedidos();
	$pedido->setValor('orderId',$array['orderId']);
    $pedido->setValor('grandTotal',$array['grandTotal']);
    $pedido->setValor('subTotal',$array['subTotal']);
    $pedido->setValor('tax',$array['tax']);
    $pedido->setValor('tipo_pagamento',$array['forma_pagamento']['tipo']);
    $pedido->setValor('troco',$array['forma_pagamento']['troco']);
    $pedido->setValor('email',$array['userDetails']['email']);
    $pedido->setValor('name',strip_tags(utf8_encode($array['userDetails']['name'])));
    $pedido->setValor('userid',$array['userId']);
    $pedido->setValor('mobileNo',$array['userDetails']['mobileNo']);
    $pedido->setValor('shippingAddress',$shippingAddress);
    $pedido->setValor('observacoes',strip_tags(utf8_encode($observacoes)));
    $pedido->setValor('createdAt',$array['createdAt']);
	$pedido->setValor('data_entrega',$data_entrega);
	$result = $pedido->insert($pedido);
	if($result>0){
	    
	     $size = count($array['cart']);
    
        for ($i = 0; $i < $size; $i++) {
        
    	    $pedidoitem = new PedidoItems2();
    	    $pedidoitem->setValor('pedido_id',$result);
    	    $pedidoitem->setValor('orderId',$array['orderId']);
            $pedidoitem->setValor('itemId',$array['cart'][$i]['item']['itemId']);
            $pedidoitem->setValor('name',strip_tags(utf8_encode($array['cart'][$i]['item']['name'])));
            $pedidoitem->setValor('price',$array['cart'][$i]['item']['price']);
            $pedidoitem->setValor('image',$array['cart'][$i]['item']['image']);
            $pedidoitem->setValor('itemQunatity',$array['cart'][$i]['item']['itemQunatity']);
            $pedidoitem->setValor('itemTotalPrice',$array['cart'][$i]['item']['itemQunatity']*$array['cart'][$i]['item']['price']);
            $result2 = $pedidoitem->insert($pedidoitem);
    	    if($result2>0){
    	        $success = array('status' => "Success", "msg" => "Pedido criado com sucesso!", "data" => $array);
    	    }else{
    		    $success = array('status' => "Failure", "msg" => "Erro ao cadastrar. Tente Novamente!.");
    	    }
    	    
        }
	}else{
		$success = array('status' => "Failure", "msg" => "Erro ao cadastrar. Tente Novamente!.");
	}
	echo json_encode($success);
	//
}

function pedidoID($param){
    $pedidoitems = new PedidoItems2();
    $pedidoitems->extras_select = 'WHERE pedido_id = "'.$param.'" AND status != 0 ORDER BY name ASC';
    $pedidoitems = $pedidoitems->findAll($pedidoitems);
	echo json_encode($pedidoitems);
}

function listarpedidosdiacorrente(){
    
    date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    //echo $dataLocal;
    
    $pedidos = new Pedidos();
	$pedidos->campos = 'pedido_id,	orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido';
	$pedidos->extras_select = 'WHERE DATE(createdAt) = "'.$dataLocal."%".'" AND status != 0 ORDER BY createdAt DESC';
	//$pedidos->extras_select = 'ORDER BY createdAt DESC';
	//$pedidos->extras_select = 'ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	//echo json_encode($pedidos);
	
	if(count($pedidos) > 0){
	    $return_arr = array();
    	foreach ($pedidos as $row) {
    		//print_r($valor);
    		//echo $row->id;
    		
    		$datahora = new DateTime($row->createdAt);
    		
    		switch ($row->tipo_pagamento) {
                case "cartao_credito":
                   $tipoPagamento = "Cartão de Crédito";
                   break;
                case "cartao_debito":
                   $tipoPagamento = "Cartão de Débito";
                   break;
                case "transferencia_bancaria":
                   $tipoPagamento = "Tranferência Bacária";
                   break;
                case "dinheiro":
                    $tipoPagamento = "Dinheiro";
                    break;
            }
            
            switch ($row->status_pagamento) {
               case 1:
                   $statusPagamento = "Pendente";
                   break;
               case 2:
                   $statusPagamento = "Pago";
                   break;
               case 3:
                   $statusPagamento = "Cancelado";
                   break;
            }
            
            switch ($row->status_pedido) {
               case 1:
                   $statusPedido = "Pendente";
                   break;
               case 2:
                   $statusPedido = "Entregue";
                   break;
               case 0:
                   $statusPedido = "Cancelado";
                   break;
            }
    		
    		$slid = array();
    		$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    	        $value['acao'],
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	
	
}

function listarpedidosagendadosData($param){

	if($param == '0000-00-00'){
		$pedidos = new Pedidos();
		$pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, data_entrega, status_pagamento, status_pedido';
		$pedidos->extras_select = 'WHERE pedido_agendado = 1 AND status != 0 ORDER BY data_entrega ASC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		$pedidos = $pedidos->findAll($pedidos);
		
		date_default_timezone_set('America/Sao_Paulo');
        $param = date('Y-m-d', time());//2020-06-08 10:50:16
    
	}else{
		date_default_timezone_set('America/Sao_Paulo');
		$dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
		//echo $dataLocal;
		
		$pedidos = new Pedidos();
		$pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, data_entrega, status_pagamento, status_pedido';
		$pedidos->extras_select = 'WHERE pedido_agendado = 1 AND DATE(data_entrega) = "'.$param."%".'"  ORDER BY data_entrega ASC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		$pedidos = $pedidos->findAll($pedidos);
	}
	
	
	
	//print_r($pedidos);
	
	//echo json_encode($pedidos);
	
	if(count($pedidos) > 0){
	    $return_arr = array();
    	foreach ($pedidos as $row) {
    		//print_r($valor);
    		//echo $row->id;
    		
    		$datahora = new DateTime($row->data_entrega);
    		
    		switch ($row->tipo_pagamento) {
                case "cartao_credito":
                   $tipoPagamento = "Cartão de Crédito";
                   break;
                case "cartao_debito":
                   $tipoPagamento = "Cartão de Débito";
                   break;
                case "transferencia_bancaria":
                   $tipoPagamento = "Tranferência Bacária";
                   break;
                case "dinheiro":
                    $tipoPagamento = "Dinheiro";
                    break;
            }
            
            switch ($row->status_pagamento) {
               case 1:
                   $statusPagamento = "<span class='label label-warning'>Pendente</span>";
                   break;
               case 2:
                   $statusPagamento = "<span class='label label-success'>Pago</span>";
                   break;
               case 0:
                   $statusPagamento = "<span class='label label-danger'>Cancelado</span>";
                   break;
            }
            
            switch ($row->status_pedido) {
               case 1:
                   $statusPedido = "<span class='label label-warning'>Pendente</span>";
                   break;
               case 2:
                   $statusPedido = "<span class='label label-success'>Entregue</span>";
                   break;
               case 0:
                   $statusPedido = "<span class='label label-danger'>Cancelado</span>";
                   break;
            }
    		
    		$slid = array();
    		//$slid['pedido_id'] = $row->pedido_id;
    		if($row->status_pedido == 0){
				$slid['acao'] = '<a target="__blank" href="https://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
			}else{
				$slid['acao'] = '<a target="__blank" href="https://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="https://superhorti.com.br/appAdmin/update/pedido.php?id='.$row->pedido_id.'&data='.$param.'" class="btn btn-default"><i class="fa fa-pencil"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		}
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		//$slid['status_pagamento'] = $row->status_pagamento;
    		//$slid['status_pedido'] = $row->status_pedido;
    		//$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/orders/printDiv/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			//$value['pedido_id'],
    			$value['acao'],
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	
}

function listarpedidosagendadosData2($param){

	if($param == '0000-00-00'){
		$pedidos = new Pedidos();
		$pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, data_entrega, status_pagamento, status_pedido';
		$pedidos->extras_select = 'WHERE pedido_agendado = 1 AND status != 0 ORDER BY data_entrega ASC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		$pedidos = $pedidos->findAll($pedidos);
	}else{
		date_default_timezone_set('America/Sao_Paulo');
		$dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
		//echo $dataLocal;
		
		$pedidos = new Pedidos();
		$pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, data_entrega, status_pagamento, status_pedido';
		$pedidos->extras_select = 'WHERE pedido_agendado = 1 AND DATE(data_entrega) = "'.$param."%".'"  ORDER BY data_entrega ASC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
		$pedidos = $pedidos->findAll($pedidos);
	}
	
	
	
	//print_r($pedidos);
	
	//echo json_encode($pedidos);
	
	if(count($pedidos) > 0){
	    $return_arr = array();
    	foreach ($pedidos as $row) {
    		//print_r($valor);
    		//echo $row->id;
    		
    		$datahora = new DateTime($row->data_entrega);
    		
    		switch ($row->tipo_pagamento) {
                case "cartao_credito":
                   $tipoPagamento = "Cartão de Crédito";
                   break;
                case "cartao_debito":
                   $tipoPagamento = "Cartão de Débito";
                   break;
                case "transferencia_bancaria":
                   $tipoPagamento = "Tranferência Bacária";
                   break;
                case "dinheiro":
                    $tipoPagamento = "Dinheiro";
                    break;
            }
            
            switch ($row->status_pagamento) {
               case 1:
                   $statusPagamento = "<span class='label label-warning'>Pendente</span>";
                   break;
               case 2:
                   $statusPagamento = "<span class='label label-success'>Pago</span>";
                   break;
               case 0:
                   $statusPagamento = "<span class='label label-danger'>Cancelado</span>";
                   break;
            }
            
            switch ($row->status_pedido) {
               case 1:
                   $statusPedido = "<span class='label label-warning'>Pendente</span>";
                   break;
               case 2:
                   $statusPedido = "<span class='label label-success'>Entregue</span>";
                   break;
               case 0:
                   $statusPedido = "<span class='label label-danger'>Cancelado</span>";
                   break;
            }
    		
    		$slid = array();
    		//$slid['pedido_id'] = $row->pedido_id;
    		if($row->status_pedido == 0){
				$slid['acao'] = '<a target="__blank" href="https://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
			}else{
				$slid['acao'] = '<a target="__blank" href="https://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="https://superhorti.com.br/appAdmin/update/pedido.php?id='.$row->pedido_id.'&data='.$param.'" class="btn btn-default"><i class="fa fa-pencil"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		}
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		//$slid['status_pagamento'] = $row->status_pagamento;
    		//$slid['status_pedido'] = $row->status_pedido;
    		//$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/orders/printDiv/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			//$value['pedido_id'],
    			$value['acao'],
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	
}

function listarpedidosdiacorrenteData($param){
    
    date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    //echo $dataLocal;
    
    $pedidos = new Pedidos();
	$pedidos->campos = 'pedido_id,	orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, data_entrega, status_pagamento, status_pedido';
	$pedidos->extras_select = 'WHERE DATE(data_entrega) = "'.$param."%".'" AND status != 0 ORDER BY data_entrega ASC';
	//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
	//$pedidos->extras_select = 'ORDER BY data_entrega DESC';
	$pedidos = $pedidos->findAll($pedidos);
	//echo json_encode($pedidos);
	
	if(count($pedidos) > 0){
	    $return_arr = array();
    	foreach ($pedidos as $row) {
    		//print_r($valor);
    		//echo $row->id;
    		
    		$datahora = new DateTime($row->data_entrega);
    		
    		switch ($row->tipo_pagamento) {
                case "cartao_credito":
                   $tipoPagamento = "Cartão de Crédito";
                   break;
                case "cartao_debito":
                   $tipoPagamento = "Cartão de Débito";
                   break;
                case "transferencia_bancaria":
                   $tipoPagamento = "Tranferência Bacária";
                   break;
                case "dinheiro":
                    $tipoPagamento = "Dinheiro";
                    break;
            }
            
            switch ($row->status_pagamento) {
               case 1:
                   $statusPagamento = "<span class='label label-warning'>Pendente</span>";
                   break;
               case 2:
                   $statusPagamento = "<span class='label label-success'>Pago</span>";
                   break;
               case 3:
                   $statusPagamento = "<span class='label label-danger'>Cancelado</span>";
                   break;
            }
            
            switch ($row->status_pedido) {
               case 1:
                   $statusPedido = "<span class='label label-warning'>Pendente</span>";
                   break;
               case 2:
                   $statusPedido = "<span class='label label-success'>Entregue</span>";
                   break;
               case 0:
                   $statusPedido = "<span class='label label-danger'>Cancelado</span>";
                   break;
            }
    		
    		$slid = array();
    		//$slid['pedido_id'] = $row->pedido_id;
    		$slid['acao'] = '<a target="__blank" href="https://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="https://superhorti.com.br/appAdmin/update/pedido.php?id='.$row->pedido_id.'&data='.$param.'" class="btn btn-default"><i class="fa fa-pencil"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = utf8_decode($row->name);
    		$slid['createdAt'] = $datahora->format('d/m/Y');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		//$slid['status_pagamento'] = $row->status_pagamento;
    		//$slid['status_pedido'] = $row->status_pedido;
    		//$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/orders/printDiv/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a>';
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			//$value['pedido_id'],
    			$value['acao'],
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	
}

function listarpedidodiacorrente($param){
    
    date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    //echo $dataLocal;
    
    $pedidos = new Pedidos();
	$pedidos->campos = 'pedido_id,	orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido';
	$pedidos->extras_select = 'WHERE pedido_id = '.$param." AND status != 0";
	$pedidos = $pedidos->findAll($pedidos);
	//echo json_encode($pedidos);
	//
	if(count($pedidos) > 0){
	    $return_arr = array();
    	foreach ($pedidos as $row) {
    		//print_r($valor);
    		//echo $row->id;
    		
    		$datahora = new DateTime($row->createdAt);
    		
    		switch ($row->tipo_pagamento) {
                case "cartao_credito":
                   $tipoPagamento = "Cartão de Crédito";
                   break;
                case "cartao_debito":
                   $tipoPagamento = "Cartão de Débito";
                   break;
                case "transferencia_bancaria":
                   $tipoPagamento = "Tranferência Bacária";
                   break;
                case "dinheiro":
                    $tipoPagamento = "Dinheiro";
                    break;
            }
            
            switch ($row->status_pagamento) {
               case 1:
                   $statusPagamento = "Pendente";
                   break;
               case 2:
                   $statusPagamento = "Pago";
                   break;
               case 3:
                   $statusPagamento = "Cancelado";
                   break;
            }
            
            switch ($row->status_pedido) {
               case 1:
                   $statusPedido = "Pendente";
                   break;
               case 2:
                   $statusPedido = "Entregue";
                   break;
               case 0:
                   $statusPedido = "Cancelado";
                   break;
            }
    		
    		$slid = array();
    		$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = '<button type="button" class="btn btn-default" onclick="editFunc('.$row->pedido_id.')" data-toggle="modal" data-target="#editModal">'.$statusPagamento.'</button>';
    		$slid['status_pedido'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/statusPedido.php?pedidoID='.$row->pedido_id.'" class="btn btn-default">'.$statusPedido.'</a>';
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    	        $value['acao'],
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	//
	
}

function listarpedidos(){
    
    /*date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    //echo $dataLocal;*/
    
    $pedidos = new Pedidos();
	$pedidos->campos = 'pedido_id,	orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido';
	$pedidos->extras_select = 'WHERE status != 0 ORDER BY createdAt DESC';
	//$pedidos->extras_select = 'ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	//echo json_encode($pedidos);
	
	if(count($pedidos) > 0){
	    $return_arr = array();
    	foreach ($pedidos as $row) {
    		//print_r($valor);
    		//echo $row->id;
    		
    		$datahora = new DateTime($row->createdAt);
    		
    		switch ($row->tipo_pagamento) {
                case "cartao_credito":
                   $tipoPagamento = "Cartão de Crédito";
                   break;
                case "cartao_debito":
                   $tipoPagamento = "Cartão de Débito";
                   break;
                case "transferencia_bancaria":
                   $tipoPagamento = "Tranferência Bacária";
                   break;
                case "dinheiro":
                    $tipoPagamento = "Dinheiro";
                    break;
            }
            
            switch ($row->status_pagamento) {
               case 1:
                   $statusPagamento = "Pendente";
                   break;
               case 2:
                   $statusPagamento = "Pago";
                   break;
               case 3:
                   $statusPagamento = "Cancelado";
                   break;
            }
            
            switch ($row->status_pedido) {
               case 1:
                   $statusPedido = "Pendente";
                   break;
               case 2:
                   $statusPedido = "Entregue";
                   break;
               case 0:
                   $statusPedido = "Cancelado";
                   break;
            }
    		
    		$slid = array();
			$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
				$value['acao'],
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	
	
}

function listarpedidospagos(){
    $pedidos = new Pedidos();
	$pedidos->extras_select = 'WHERE status_pagamento = 2 AND status != 0';
	$pedidos = $pedidos->findAll($pedidos);
	echo json_encode($pedidos);
	//print_r($pedidos);
}

function itenspedido($param){
    $itenspedido = new PedidoItems2();
	$itenspedido->extras_select = 'WHERE id_pedidos = '.$param;
	$itenspedido = $itenspedido->findAll($itenspedido);
	echo json_encode($itenspedido);
}

function clientes(){
    $clientes = new Clientes();
    $clientes->extras_select = 'ORDER BY name ASC';
    $clientes = $clientes->findAll($clientes);
    //echo json_encode($clientes);
    
    $return_arr = array();
	foreach ($clientes as $row) {
        $slid = array();
    	$slid['name'] = utf8_decode($row->name);
    	$slid['email'] = $row->email;
    	$slid['mobileNo'] = $row->mobileNo;
    	//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    	array_push($return_arr,$slid);    
	}
	
	foreach ($return_arr as $key => $value) {
	    $result['data'][$key] = array(
			$value['name'],
			$value['email'],
			$value['mobileNo']
		);
	}
	echo json_encode($result);
}

function pedidos(){
    
    $request = \Slim\Slim::getInstance()->request();
	$array = json_decode($request->getBody(),true);
	
	$result = json_validate($request->getBody());
	
	if($result > 0){
	    
	    //
	    //salvaTXT($request->getBody());
    	//salvaTXT($array['pedidoAgendado']);
    	//salvaTXT($array['pedidoAgendado']);
    	//
    	///echo $request->getBody();
    	
    	//
    	//cadastra o cliente antes
    	$cliente = new Clientes();
        $cliente->setValor('name',strip_tags(utf8_encode($array['userDetails']['name'])));
        $cliente->setValor('email',$array['userDetails']['email']);
        $cliente->setValor('mobileNo',$array['userDetails']['mobileNo']);
        $cliente->setValor('userId',$array['userDetails']['userid']);
        $cliente->insert($cliente);
        
        $shippingAddress = (isset($array['shippingAddress'])) ? json_encode($array['shippingAddress']) : NULL;
    	
    	$observacoes = (isset($array['observacoes'])) ? $array['observacoes'] : NULL;
    	
    	$data_entrega = (isset($array['dataPedidoAgendado'])) ? $array['dataPedidoAgendado'] : NULL;
    	
    	$pedido = new Pedidos();
    	$pedido->setValor('orderId',$array['orderId']);
        $pedido->setValor('grandTotal',$array['grandTotal']);
        $pedido->setValor('subTotal',$array['subTotal']);
        $pedido->setValor('tax',$array['tax']);
        $pedido->setValor('tipo_pagamento',$array['forma_pagamento']['tipo']);
        $pedido->setValor('troco',$array['forma_pagamento']['troco']);
        $pedido->setValor('email',$array['userDetails']['email']);
        $pedido->setValor('name',strip_tags(utf8_encode($array['userDetails']['name'])));
        $pedido->setValor('userid',$array['userId']);
        $pedido->setValor('mobileNo',$array['userDetails']['mobileNo']);
        $pedido->setValor('shippingAddress',$shippingAddress);
        $pedido->setValor('observacoes',strip_tags(utf8_encode($observacoes)));
        $pedido->setValor('createdAt',$array['createdAt']);
    	$pedido->setValor('data_entrega',$data_entrega);
    	$pedido->setValor('pedido_agendado',$array['pedidoAgendado']);
    	$pedido->setValor('log_pedidos',$request->getBody());
    	$result = $pedido->insert($pedido);
    	
    	if($result>0){
    	    
    	     $size = count($array['cart']);
        
            for ($i = 0; $i < $size; $i++) {
                
                if($array['cart'][$i]['item']['price'] != 0){
                    $pedidoitem = new PedidoItems();
            	    $pedidoitem->setValor('pedido_id',$result);
            	    $pedidoitem->setValor('orderId',$array['orderId']);
                    $pedidoitem->setValor('itemId',$array['cart'][$i]['item']['itemId']);
                    $pedidoitem->setValor('name',strip_tags(utf8_encode($array['cart'][$i]['item']['name'])));
                    $pedidoitem->setValor('price',$array['cart'][$i]['item']['price']);
                    $pedidoitem->setValor('image',$array['cart'][$i]['item']['image']);
                    $pedidoitem->setValor('itemQunatity',$array['cart'][$i]['item']['itemQunatity']);
                    $pedidoitem->setValor('itemTotalPrice',$array['cart'][$i]['item']['itemQunatity']*$array['cart'][$i]['item']['price']);
                    $result2 = $pedidoitem->insert($pedidoitem);
            	    /*if($result2>0){
            	        //$success = array('status' => "Success", "msg" => "Pedido criado com sucesso!", "data" => $array);
            	        $success = 1;
            	    }else{
            		    //$success = array('status' => "Failure", "msg" => "Erro ao cadastrar. Tente Novamente!.");
            		    $success = 0;
            	    }*/
                }
        	    
            }
            
            $success = 1;
            
    	}else{
    		//$success = array('status' => "Failure", "msg" => "Erro ao cadastrar. Tente Novamente!.");
    		
    		$ticket = new TicketPedido();
            $ticket->valorpk = $pedidosArray[$j]['pedido_id'];
            $ticket->setValor('ticket',2);
            $ticket->update($ticket);
    		
    		$success = 1;
    	}
    	echo json_encode($success);
    	//
    	
    	//$success = array('status' => "OK", "msg" => "Tudo certo!");
    	//echo json_encode($success);
    	//echo 1;
    	
	}else{
        echo 0;
	}
	
	
}

function msgsApp($param) {
    
    echo '{"msg":"'.$param.'"}';
    
}

function googleMaps($origins){
    
    if(isset($origins) AND !empty($origins)){
		//$origins = '74080-240';//casa
		$destinations = '74675-090';//CEASA - Goiânia - GO.
		$mode = 'CAR';
		$language = 'PT';
		$sensor = false;
		
		$key = 'AIzaSyAjayuD_XLSuXptvzHPfsAt2OBEFDC4lOE';
		
		$resultado =  file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$origins.'&destinations='.$destinations.'&mode='.$mode.'&language='.$language.'&sensor=false&key='.$key);
		
		$resultado2 =  file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$origins2.'&destinations='.$destinations.'&mode='.$mode.'&language='.$language.'&sensor=false&key='.$key);
		
		$dados = json_decode($resultado,true);
		
		//echo $resultado;
		
		//$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
		//echo $array[0];
		//print_r($array);
		//echo(count($array));
		
		//print_r($dados);
		
		if($dados['status'] == 'OK'){
		
			//echo json_encode(($dados['rows'][0]['elements'][0]['distance']['text']));
			$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
			
			//echo $array[0];
			
			if(isset($array[0]) AND !empty($array[0])){
			
				if($array[1] == "km"){
					if($array[0] <= 5){
						echo json_encode(0.00);
					}else if($array[0] <= 10){
						echo json_encode(15.00);
					}else if($array[0] <= 25){
						echo json_encode(15.00);
					}else if($array[0] <= 30){
						echo json_encode(25.00);
					}else{
						echo json_encode("Não fazemos entrega nessa região!");
					}
				}else{
					echo json_encode(0.00);
				}
			
			}else{
				//echo json_encode('Digite um CEP válido');
				echo json_encode("Não fazemos entrega nessa região!");
				//echo json_encode('CEP inválido ou não fazemos entrega nessa região! Tente novamente, ou tente outro endereço para entrega!');
			}
		
		}else{
			//echo json_encode('Erro ao acessar o sistema, tente mais tarde!');
			echo json_encode('Digite um CEP válido');
		}
	}else{
		echo json_encode('Digite um CEP válido');
	}
}

function googleMaps2($origins){
    
    if(isset($origins) AND !empty($origins)){
		//$origins = '74080-240';//casa
		$destinations = '74675-090';//CEASA - Goiânia - GO.
		$mode = 'CAR';
		$language = 'PT';
		$sensor = false;
		
		$key = 'AIzaSyAjayuD_XLSuXptvzHPfsAt2OBEFDC4lOE';
		
		$resultado =  file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$origins.'&destinations='.$destinations.'&mode='.$mode.'&language='.$language.'&sensor=false&key='.$key);
		
		$resultado2 =  file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$origins2.'&destinations='.$destinations.'&mode='.$mode.'&language='.$language.'&sensor=false&key='.$key);
		
		$dados = json_decode($resultado,true);
		
		echo $resultado;
		
		//$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
		//echo $array[0];
		//print_r($array);
		//echo(count($array));
		
		//print_r($dados);
		
		//
		if($dados['status'] == 'OK'){
		
			echo $dados['origin_addresses'][0];
			
			//echo json_encode(($dados['rows'][0]['elements'][0]['distance']['text']));
			$array = explode(' ', $dados['rows'][0]['elements'][0]['distance']['text']);
			
			//echo $array[0];
			
			/*
			if(isset($array[0]) AND !empty($array[0])){
			
				if($array[1] == "km"){
					if($array[0] <= 5){
						echo json_encode(0.00);
					}else if($array[0] <= 10){
						echo json_encode(15.00);
					}else if($array[0] <= 25){
						echo json_encode(15.00);
					}else if($array[0] <= 30){
						echo json_encode(25.00);
					}else{
						echo json_encode("Não fazemos entrega nessa região!");
					}
				}else{
					echo json_encode(0.00);
				}
			
			}else{
				//echo json_encode('Digite um CEP válido');
				echo json_encode("Não fazemos entrega nessa região!");
				//echo json_encode('CEP inválido ou não fazemos entrega nessa região! Tente novamente, ou tente outro endereço para entrega!');
			}
			*/
		
		}else{
			//echo json_encode('Erro ao acessar o sistema, tente mais tarde!');
			echo json_encode('Digite um CEP válido');
		}
		//
	}else{
		echo json_encode('Digite um CEP válido');
	}
}

function sliders(){
	$slider = new Sliders();
	$slider->campos = 'id, name, thumb, text';
	$slider->extras_select = 'WHERE status = 1';
	$sliders = $slider->findAll($slider);
	//echo json_encode($sliders);
	$return_arr = array();
	foreach ($sliders as $row) {
		//print_r($valor);
		//echo $row->id;
		$slid = array();
		$slid['id'] = $row->id;
		$slid['name'] = strip_tags(utf8_encode($row->name));
		$slid['text'] = strip_tags(utf8_encode($row->text));
		$slid['thumb'] = strip_tags($row->thumb);
		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
		array_push($return_arr,$slid);
	}
	echo json_encode($return_arr);
}

function category(){
	$category = new Category();
	$category->campos = 'id, name, description, thumb, thumb_menor, color';
	$categorias = $category->findAll($category);
	//echo json_encode($categorias);
	$return_arr = array();
	foreach ($categorias as $row) {
		//print_r($valor);
		//echo $row->id;
		$categ = array();
		$categ['id'] = $row->id;
		$categ['name'] = strip_tags($row->name);
		$categ['description'] = strip_tags(utf8_encode($row->description));
		$categ['thumb'] = strip_tags($row->thumb);
		$categ['thumb_menor'] = strip_tags($row->thumb_menor);
		$categ['color'] = strip_tags($row->color);
		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
		array_push($return_arr,$categ);
	}
	echo json_encode($return_arr);
}

function offers(){

	$products = new Products();
	$products->campos = 'id, category_id, name, description, price, image, offerPercentage';
	$products->extras_select = 'WHERE offer  = 1 AND active = 1';
	$produtos = $products->findAll($products);
	//print_r($produtos);
	
	$return_arr = array();
	foreach ($produtos as $row) {
		//print_r($valor);
		//echo $row->id;
		$product = array();
		$product['id'] = $row->id;
		$product['category_id'] = $row->category_id;
		$product['name'] = strip_tags(utf8_encode($row->name));
		$product['description'] = strip_tags(utf8_encode($row->description));
		$product['price'] = $row->price;
		$product['offerPercentage'] = $row->offerPercentage;
		$product['priceOffer'] = $row->price - ($row->price*$row->offerPercentage)/100;
		$product['image'] = strip_tags($row->image);
		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
		array_push($return_arr,$product);
	}
	
	echo json_encode($return_arr);

}

function products(){
	$category = new Category();
	$category->campos = 'id, name, thumb';
	$categorias = $category->findAll($category);
	//print_r($categorias);
	
	$products = new Products();
	$products->campos = 'id, category_id, name, description, price, image, offer, offerPercentage';
	$products->extras_select = 'WHERE active = 1';
	$produtos = $products->findAll($products);
	//print_r($produtos);
	
	$return_arr = array();
	foreach ($produtos as $row) {
		//print_r($valor);
		//echo $row->id;
		$product = array();
		$product['id'] = $row->id;
		$product['category_id'] = $row->category_id;
		$product['name'] = strip_tags(utf8_encode($row->name));
		$product['description'] = strip_tags(utf8_encode($row->description));
		$product['price'] = $row->price;
		$product['offer'] = $row->offer;
		$product['priceOffer'] = $row->price - ($row->price*$row->offerPercentage)/100;
		$product['offerPercentage'] = $row->offerPercentage;
		$product['image'] = strip_tags($row->image);
		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
		array_push($return_arr,$product);
	}
	
	echo json_encode($return_arr);

	///echo json_encode($produtos);
	/*
	foreach ($produtos as $valor_do_array) {
		//print_r($valor_do_array);
		echo json_encode($valor_do_array, JSON_UNESCAPED_UNICODE);
	}
	*/
	//echo json_encode($produtos, JSON_UNESCAPED_UNICODE);	
	//$dados = json_decode(json_encode($result),true);
	//echo json_encode(json_decode(json_encode($result),true));
}

function product($item){
	$array = explode('&', $item);

	
	///$teste = "'[".'"'.$item.'"'."]'";
	
	$teste = "'[".'"'.$array[0].'"'."]'";
	
	if($array[1] == 'a'){
		$teste2 = " ORDER BY price*100 ASC"; 
	}else if($array[1] == 'd'){
		$teste2 = " ORDER BY price*100 DESC";
	}else{
		$teste2 = " ORDER BY name ASC";
	}
		
	//echo $teste;

	$products = new Products();
	$products->campos = 'id, category_id, name, description, price, image';
	$products->extras_select = 'WHERE active = 1 AND category_id = ' . $teste . $teste2;
	$produtos = $products->findAll($products);
	//print_r($produtos);
	
	$return_arr = array();
	foreach ($produtos as $row) {
		//print_r($valor);
		//echo $row->id;
		$product = array();
		$product['id'] = $row->id;
		$product['category_id'] = $row->category_id;
		$product['name'] = strip_tags($row->name);
		$product['description'] = strip_tags($row->description);
		$product['price'] = $row->price;
		$product['image'] = strip_tags($row->image);
		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
		array_push($return_arr,$product);
	}
	echo json_encode($return_arr);
}

function productID($item){
	$products = new Products();
	$products->campos = 'id, category_id, name, description, price, image';
	$products->extras_select = 'WHERE id = '.$item;
	$produtos = $products->findAll($products);
	//print_r($produtos);
	
	$return_arr = array();
	foreach ($produtos as $row) {
		//print_r($valor);
		//echo $row->id;
		$product = array();
		$product['id'] = $row->id;
		$product['category_id'] = $row->category_id;
		$product['name'] = strip_tags($row->name);
		$product['description'] = strip_tags($row->description);
		$product['price'] = $row->price;
		$product['image'] = strip_tags($row->image);
		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
		array_push($return_arr,$product);
	}
	echo json_encode($return_arr);
}

function products_(){
	$products = new Products_();
	//$products->campos = 'id, category_id, images, name, price, rating, sale_price, short_description, thumb';
	//$products->extras_select = 'WHERE active = 1';
	$produtos = $products->findAll($products);
	//print_r($produtos);
	echo json_encode($produtos);
	//echo json_encode($produtos, JSON_UNESCAPED_UNICODE);	
	//$dados = json_decode(json_encode($result),true);
	//echo json_encode(json_decode(json_encode($result),true));
}

function promotions(){
	$promotions = new Promotions();
	//$products->campos = 'id, category_id, images, name, price, rating, sale_price, short_description, thumb';
	//$products->extras_select = 'WHERE active = 1';
	$promotions = $promotions->findAll($promotions);
	//print_r($promotions);
	echo json_encode($promotions);
	//echo retiraCaracteres(json_encode($promotions));
}

function json_validate($string){
    // decode the JSON data
    $result = json_decode($string);
	//$result = 1;

    // switch and check possible JSON errors
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $error = ''; // JSON e valido // Nenhum erro ocorreu
            break;
        case JSON_ERROR_DEPTH:
            $error = 'A profundidade maxima da pilha foi excedida.';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            $error = 'JSON invalido ou malformado.';
            break;
        case JSON_ERROR_CTRL_CHAR:
            $error = 'Erro de caractere de controle, possivelmente codificado incorretamente.';
            break;
        case JSON_ERROR_SYNTAX:
            $error = 'Erro de sintaxe, JSON malformado.';
            break;
        // PHP >= 5.3.3
        case JSON_ERROR_UTF8:
            $error = 'Caracteres UTF-8 malformados, possivelmente codificados incorretamente.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_RECURSION:
            $error = 'Uma ou mais referências recursivas no valor a ser codificado.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_INF_OR_NAN:
            $error = 'Um ou mais valores NAN ou INF no valor a ser codificado.';
            break;
        case JSON_ERROR_UNSUPPORTED_TYPE:
            $error = 'Um valor de um tipo que não pode ser codificado foi fornecido.';
            break;
        default:
            $error = 'Ocorreu um erro JSON desconhecido.';
            break;
    }

    if ($error !== '') {
        // throw the Exception or exit // or whatever :)
        ///exit($error);
        return 0;
    }else{
        // está tudo bem
        return 1;
    }

}

function retiraCaracteres($valor){
	$caracteres = array("[", "]");
	return str_replace($caracteres, "", $valor);
}

function salvaTXT($param){
	$name = 'arquivo.txt';
	$text = $param;
	$file = fopen($name, 'a');
	fwrite($file, $text);
	fclose($file);
}