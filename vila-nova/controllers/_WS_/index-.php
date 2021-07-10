<?php

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

$app->get('/msgsApp/:param', 'msgsApp');

$app->get('/settings', 'settings');

$app->get('/pedidosDoDia', 'pedidosDoDia');
$app->get('/pedidosUserID/:param', 'pedidosUserID');
$app->get('/pedidosAgendadosUserID/:param', 'pedidosAgendadosUserID');
$app->get('/pedidoID/:param', 'pedidoID');

$app->get('/googleMaps/:origins', 'googleMaps');
$app->get('/getGeoCode/:param', 'getGeoCode');

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

$app->get('/listarpedidodiacorrente/:param', 'listarpedidodiacorrente');

$app->get('/listarpedidospagos','listarpedidospagos');

$app->get('/itenspedido/:param', 'itenspedido');

$app->post('/pedidos', 'pedidos');

$app->post('/cancelPedido', 'cancelPedido');

$app->post('/relatorioProdutosDoDia', 'relatorioProdutosDoDia');

$app->post('/editPedidosAgendadosUserID', 'editPedidosAgendadosUserID');

//metodos de chamadas da apei Slim Framework fim
$app->run();// start api

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
	
	//echo $request->getBody();
	
	//salvaTXT($request->getBody());
	
	$array = explode('=', $request->getBody());
	
	echo json_encode($array[1]);
	
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
	$result = $pedido->update($pedido);
	if($result>0){
		$success = array('status' => "Success", "msg" => "Peddo cancelado com sucesso!.");
	}else{
		$success = array('status' => "Failure", "msg" => "Erro ao cancelar pedido. Tente Novamente!.");
	}
	echo json_encode($success);
}

function testes(){
    
    $key = 'AIzaSyAjayuD_XLSuXptvzHPfsAt2OBEFDC4lOE';
    
    //$address = 'avenida+gustavo+paiva,maceio,alagoas,brasil';
    
    $a = "Rua do Café, sn, solar viller, condomínio bosque dos buritis, Goiânia - GO"; // Pega parâmetro
    $addr = str_replace(" ", "+", $a); // Substitui os espaços por + "Rua+Paulo+Guimarães,+São+Paulo+-+SP" conforme padrão 'maps.google.com'
    $address = utf8_encode($addr);

    $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.$key.'&address='.$address.'&sensor=false');
    
    
    //print_r($geocode);
    
    $output= json_decode($geocode);
    
    $lat = $output->results[0]->geometry->location->lat;
    $long = $output->results[0]->geometry->location->lng;
    
    echo '{"latitude":"'.$lat.'","longitude":"'.$long.'"}';
    
	//echo json_encode("TOKEN INVÁLIDO!");
	
	/*date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d H:i:s', time());//2020-06-08 10:50:16
	
	echo $dataLocal;*/
	
	/*date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    echo $dataLocal;*/
	
	/*$string = "date=2020-06-23";

	$array = explode('=', $string);
	
	echo $array[1];*/
    
	/*
    $pedidos = new Pedidos();
	$pedidos->campos = 'pedido_id,	orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido';
	$pedidos->extras_select = 'ORDER BY createdAt DESC';
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
               case 3:
                   $statusPedido = "Cancelado";
                   break;
            }
    		
    		$slid = array();
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido'],
    			$value['acao']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	*/
	
	/*$pedidos = new Pedidos();
	$pedidos->campos = 'pedidos.pedido_id,	pedidos.orderId, email, pedidos.name AS cliente, pedido_items.name AS produto, itemId, image, itemQunatity, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, count(*) as total';
	//$pedidos->campos = 'pedidos.pedido_id, itemId, itemQunatity, createdAt, status_pagamento, status_pedido, count(*) as total';
	//$pedidos->extras_select = 'INNER JOIN pedido_items ON pedidos.pedido_id = pedido_items.pedido_id WHERE DATE(createdAt) = "2020-06-08" GROUP BY itemId ORDER BY createdAt DESC';
	$pedidos->extras_select = 'INNER JOIN pedido_items ON pedidos.pedido_id = pedido_items.pedido_id WHERE DATE(createdAt) = "2020-06-08" GROUP BY itemId ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	$pedidosJson = json_encode($pedidos);
	$pedidosArray = json_decode($pedidosJson,true);
	
	print_r($pedidosArray);*/
	
	
	/*$resultado =  file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=geoCordinates&radius=500&key=AIzaSyAjayuD_XLSuXptvzHPfsAt2OBEFDC4lOE');
	echo $resultado;*/
	
	/*
	$json = '{
       "grandTotal":47.5,
       "subTotal":47.5,
       "tax":0,
       
       "cart":[
          {
             "itemTotalPrice":10.5,
             "item":{
                "itemId":"20",
                "extraOptions":[
    
                ],
                "price":"3.50",
                "name":"Abobora Italia",
                "image":"assets/images/product_image/5e823b742decd.jpg",
                "itemQunatity":3
             }
          },
          {
             "itemTotalPrice":11,
             "item":{
                "itemId":"17",
                "extraOptions":[
    
                ],
                "price":"5.50",
                "name":"Abobora Kabutia",
                "image":"assets/images/product_image/5e823b7e7d68f.jpg",
                "itemQunatity":2
             }
          },
          {
             "itemTotalPrice":3.5,
             "item":{
                "itemId":"19",
                "extraOptions":[
    
                ],
                "price":"3.50",
                "name":"Abobora Menina",
                "image":"assets/images/product_image/5e823b98a1993.jpg",
                "itemQunatity":1
             }
          },
          {
             "itemTotalPrice":8,
             "item":{
                "itemId":"53",
                "extraOptions":[
    
                ],
                "price":"4.00",
                "name":"Agriao",
                "image":"assets/images/product_image/5e823c1f92f73.jpg",
                "itemQunatity":2
             }
          },
          {
             "itemTotalPrice":5.5,
             "item":{
                "itemId":"80",
                "extraOptions":[
    
                ],
                "price":"5.50",
                "name":"Abacaxi",
                "image":"assets/images/product_image/5e823b4ad4254.jpg",
                "itemQunatity":1
             }
          },
          {
             "itemTotalPrice":9,
             "item":{
                "itemId":"69",
                "extraOptions":[
    
                ],
                "price":"9.00",
                "name":"Ameixa",
                "image":"assets/images/product_image/5e823eebeb793.jpg",
                "itemQunatity":1
             }
          }
       ],
       
       "shippingAddress":{
          "bairro":"fgdfg",
          "cep":"74080-240",
          "cidade":"qweqw",
          "contato":"(44) 44444-4444",
          "endereco":"hjhghj",
          "frete":"15",
          "nome":"sdasd"
       },
       
       "observacoes":"Observação 111",
       
       "orderId":"03062020221443",
       
       "userDetails":{
          "email":"dddougs@hotmail.com",
          "name":"Douglas S",
          "userid":"aSFZWa5DLVSSYoabx07XPQYxvFD3",
          "mobileNo":"(62) 98312-4955"
       },
       
       "createdAt":"2020-06-03 22:15:12",
       
       "forma_pagamento":{
          "tipo":"dinheiro",
          "troco":"100,00"
       }
    }';
    
    $array = json_decode($json,true);
    
    $cliente = new Clientes();
    $cliente->setValor('name',strip_tags(utf8_encode($array['userDetails']['name'])));
    $cliente->setValor('email',$array['userDetails']['email']);
    $cliente->setValor('mobileNo',$array['userDetails']['mobileNo']);
    $cliente->setValor('userId',$array['userDetails']['userid']);
    $result = $cliente->insert($cliente);
    
    if(is_numeric($result)){
        echo $result;
    }else{
        echo json_encode("Já cadastrado!");
    }
    
    //echo $result;
    */
    
    /*echo ($array['grandTotal']);
    echo ($array['subTotal']);
    echo ($array['tax']);
    
    echo ($array['userDetails']['email']);
    echo (strip_tags(utf8_encode($array['userDetails']['name'])));
    echo ($array['userDetails']['userid']);
    echo ($array['userDetails']['mobileNo']);
    
    echo ($array['observacoes']);
    echo ($array['orderId']);
    echo ($array['createdAt']);
    
    echo ($array['forma_pagamento']['tipo']);
    echo ($array['forma_pagamento']['troco']);*/
    
    //print_r($array['shippingAddress']);
    //echo(json_encode($array['shippingAddress']));
    
    /*$pedido = new Pedidos();
	$pedido->setValor('orderId',$array['orderId']);
    $pedido->setValor('grandTotal',$array['grandTotal']);
    $pedido->setValor('subTotal',$array['subTotal']);
    $pedido->setValor('tax',$array['tax']);
    $pedido->setValor('tipo_pagamento',$array['forma_pagamento']['tipo']);
    $pedido->setValor('troco',$array['forma_pagamento']['troco']);
    $pedido->setValor('email',$array['userDetails']['email']);
    $pedido->setValor('name',strip_tags(utf8_encode($array['userDetails']['name'])));
    $pedido->setValor('userid',$array['userDetails']['userid']);
    $pedido->setValor('mobileNo',$array['userDetails']['mobileNo']);
    $pedido->setValor('shippingAddress',json_encode($array['shippingAddress']));
    $pedido->setValor('observacoes',strip_tags(utf8_encode($array['observacoes'])));
    $pedido->setValor('createdAt',$array['createdAt']);
	$result = $pedido->insert($pedido);
	
	echo $result;*/

    //print_r($array['cart']);
    
    /*
    $size = count($array['cart']);
    
    for ($i = 0; $i < $size; $i++) {
        
        //echo($array['cart'][$i]['item']['itemId']);
        ///print_r($array['cart'][$i]['item']);
        
        $pedidoitem = new PedidoItems();
	    $pedidoitem->setValor('id_pedidos',1);
	    $pedidoitem->setValor('orderId',$array['cart'][$i]['item']['itemId']);
        $pedidoitem->setValor('itemId',$array['cart'][$i]['item']['itemId']);
        $pedidoitem->setValor('name',strip_tags(utf8_encode($array['cart'][$i]['item']['name'])));
        $pedidoitem->setValor('price',$array['cart'][$i]['item']['price']);
        $pedidoitem->setValor('image',$array['cart'][$i]['item']['image']);
        $pedidoitem->setValor('itemQunatity',$array['cart'][$i]['item']['itemQunatity']);
        $pedidoitem->setValor('itemTotalPrice',$array['cart'][$i]['item']['itemQunatity']*$array['cart'][$i]['item']['price']);
        $result2 = $pedidoitem->insert($pedidoitem);
        
        echo $result2;
        
    }
    */
    
    /*foreach ($array['cart'] as $value) {
        //print_r($value);
        foreach ($array['cart'] as $value) {
            echo $value['itemTotalPrice'];
            echo $value['item']['itemId'];
            echo $value['item']['price'];
            echo strip_tags(utf8_encode($value['item']['name']));
            echo $value['item']['image'];
            echo $value['item']['itemQunatity'];
            //print_r($value['item']);
            echo '------------------------------------------------------------';
        }
    }*/

    //print_r($array);
    
    //echo $json;
}

function teste($param){
	echo '{"msg":"'.$param.'"}';
}
//testes fim

function settings(){
    
    $settings = new Settings();
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
	$pedidos->extras_select = 'INNER JOIN pedido_items ON pedidos.pedido_id = pedido_items.pedido_id WHERE DATE(createdAt) = "'.$param.'" GROUP BY itemId ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	$pedidosJson = json_encode($pedidos);
	$pedidosArray = json_decode($pedidosJson,true);
	
	//print_r($pedidosArray);
	
	echo $pedidosJson;
}

function pedidosDoDia(){
    
    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Sao_Paulo');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    //$dataLocal = date('Y-m-d H:i:s', time());//2020-06-08 10:50:16
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    
    //echo $dataLocal;
    
    $pedidos = new Pedidos();
	$pedidos->extras_select = 'WHERE DATE(createdAt) = "'.$dataLocal."%".'" ORDER BY createdAt DESC';
	//$pedidos->extras_select = 'ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	///echo json_encode($pedidos);
	echo count($pedidos);
    
}

function pedidosUserID($param){
    $pedidos = new Pedidos();
    $pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax , tipo_pagamento, troco, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, data_entrega';
	$pedidos->extras_select = 'WHERE userid = "'.$param.'" AND (data_entrega = 0000-00-00 OR data_entrega = "") AND status_pedido = 1 ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	echo json_encode($pedidos);
}

function pedidosAgendadosUserID($param){
    $pedidos = new Pedidos();
    $pedidos->campos = 'pedido_id, orderId, grandTotal, subTotal, tax , tipo_pagamento, troco, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido, data_entrega, data_cancel_entrega';
	$pedidos->extras_select = 'WHERE userid = "'.$param.'" AND (data_entrega != 0000-00-00 OR data_entrega != "") AND status_pedido = 1 ORDER BY createdAt DESC';
	$pedidos = $pedidos->findAll($pedidos);
	echo json_encode($pedidos);
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
    $pedidoitems = new PedidoItems();
    $pedidoitems->extras_select = 'WHERE pedido_id = "'.$param.'" ORDER BY name ASC';
    $pedidoitems = $pedidoitems->findAll($pedidoitems);
	echo json_encode($pedidoitems);
}

function listarpedidosdiacorrente(){
    
    date_default_timezone_set('America/Sao_Paulo');
    $dataLocal = date('Y-m-d', time());//2020-06-08 10:50:16
    //echo $dataLocal;
    
    $pedidos = new Pedidos();
	$pedidos->campos = 'pedido_id,	orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido';
	$pedidos->extras_select = 'WHERE DATE(createdAt) = "'.$dataLocal."%".'" ORDER BY createdAt DESC';
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
               case 3:
                   $statusPedido = "Cancelado";
                   break;
            }
    		
    		$slid = array();
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido'],
    			$value['acao']
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
	$pedidos->campos = 'pedido_id,	orderId, grandTotal, subTotal, tax, tipo_pagamento, troco, email, name, userid, mobileNo, shippingAddress, observacoes, createdAt, status_pagamento, status_pedido';
	$pedidos->extras_select = 'WHERE DATE(createdAt) = "'.$param."%".'" ORDER BY createdAt DESC';
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
               case 3:
                   $statusPedido = "<span class='label label-danger'>Cancelado</span>";
                   break;
            }
    		
    		$slid = array();
    		//$slid['pedido_id'] = $row->pedido_id;
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		//$slid['status_pagamento'] = $row->status_pagamento;
    		//$slid['status_pedido'] = $row->status_pedido;
    		//$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/orders/printDiv/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a>';
    		$slid['acao'] = '<a target="__blank" href="https://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="https://superhorti.com.br/appAdmin/update/pedido.php?id='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			//$value['pedido_id'],
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido'],
    			$value['acao']
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
	$pedidos->extras_select = 'WHERE pedido_id = '.$param;
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
               case 3:
                   $statusPedido = "Cancelado";
                   break;
            }
    		
    		$slid = array();
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = '<button type="button" class="btn btn-default" onclick="editFunc('.$row->pedido_id.')" data-toggle="modal" data-target="#editModal">'.$statusPagamento.'</button>';
    		$slid['status_pedido'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/statusPedido.php?pedidoID='.$row->pedido_id.'" class="btn btn-default">'.$statusPedido.'</a>';
    		$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a><!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido'],
    			$value['acao']
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
	$pedidos->extras_select = 'ORDER BY createdAt DESC';
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
               case 3:
                   $statusPedido = "Cancelado";
                   break;
            }
    		
    		$slid = array();
    		$slid['orderId'] = $row->orderId;
    		$slid['name'] = strip_tags(utf8_encode($row->name));
    		$slid['createdAt'] = $datahora->format('d/m/Y H:i:s');;
    		$slid['grandTotal'] = $row->grandTotal;
    		$slid['tipo_pagamento'] = $tipoPagamento;
    		$slid['status_pagamento'] = $statusPagamento;
    		$slid['status_pedido'] = $statusPedido;
    		$slid['acao'] = '<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-print"></i></a> <!--<a href="http://superhorti.com.br/appAdmin/orders/update/'.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>--> <!--<a target="__blank" href="http://superhorti.com.br/appAdmin/pedidos/index.php?printDiv='.$row->pedido_id.'" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->';
    		//echo json_encode($product, JSON_UNESCAPED_UNICODE);
    		array_push($return_arr,$slid);
    	}
    	//echo '{"data": '.json_encode($return_arr).'}';
    	//print_r($return_arr);
    	
    	foreach ($return_arr as $key => $value) {
    	    $result['data'][$key] = array(
    			$value['orderId'],
    			$value['name'],
    			$value['createdAt'],
    			$value['grandTotal'],
    			$value['tipo_pagamento'],
    			$value['status_pagamento'],
    			$value['status_pedido'],
    			$value['acao']
    		);
    	}
    	echo json_encode($result);
	}else{
	    echo '{"data": '.json_encode($pedidos).'}';
	}
	
	
}

function listarpedidospagos(){
    $pedidos = new Pedidos();
	$pedidos->extras_select = 'WHERE status_pagamento = 2';
	$pedidos = $pedidos->findAll($pedidos);
	echo json_encode($pedidos);
	//print_r($pedidos);
}

function itenspedido($param){
    $itenspedido = new PedidoItems();
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
    	$slid['name'] = strip_tags(utf8_encode($row->name));
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
			echo json_encode("ERROR!");
		}
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
	$category->campos = 'id, name, description, thumb';
	$categorias = $category->findAll($category);
	//echo json_encode($categorias);
	$return_arr = array();
	foreach ($categorias as $row) {
		//print_r($valor);
		//echo $row->id;
		$categ = array();
		$categ['id'] = $row->id;
		$categ['name'] = strip_tags(utf8_encode($row->name));
		$categ['description'] = strip_tags(utf8_encode($row->description));
		$categ['thumb'] = strip_tags($row->thumb);
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
	$products->extras_select = 'WHERE category_id = ' . $teste . $teste2;
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
		$product['name'] = strip_tags(utf8_encode($row->name));
		$product['description'] = strip_tags(utf8_encode($row->description));
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