<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

//$BASE_URL = "http://testeseetc.com/projeto-ds/dsplusframework/vila-nova/controllers/ws/";
$BASE_URL ="https://projetro.com/ws/vila-nova/controllers/ws/";

$SITE_KEY = 'yourSecretKey';

//require_once("../../sys/Config.php");
require_once("../../sys/DB.class.php");
require_once("../../sys/DAO.class.php");
require_once("../../sys/Modal.class.php");

require_once ("../../models/Users.class.php");
require_once ("../../models/Feed.class.php");

require_once ("../../models/Logs.class.php");
require_once ("../../models/Usuarios.class.php");
require_once ("../../models/ControleFisiologico.class.php");

//Cabecalho api Slim Framework inicio
require_once ("../../apis/Slim/Slim.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->error(function (\Exception $e) use ($app) {
    $app->render('error.php');
});
$app->response()->header('Content-Type', 'application/json;charset=utf-8');
//Cabecalho api Slim Framework fim

$app->get('/', function () { //protecao da pasta controllers/webservice
  //echo "ACESSO RESTRITO";
  echo '{"error":{"text":"Você não tem permissão!"}}';
});

//metodos de chamadas da api Slim Framework inicio

$app->get('/testes', 'testes');

$app->post('/login','login');
$app->post('/signup','signup');

$app->post('/dados_usuario','dados_usuario');
$app->post('/alterar_dados_usuario','alterar_dados_usuario');
$app->post('/alterar_senha_usuario','alterar_senha_usuario');
$app->post('/alterar_foto_usuario','alterar_foto_usuario');

$app->post('/listarDadosFadigaRecuperacao','listarDadosFadigaRecuperacao');
$app->post('/cadastrarGerenciamentoFadigaRecuperacao','cadastrarGerenciamentoFadigaRecuperacao');

$app->post('/listarAtletas','listarAtletas');
$app->post('/listarDadosAtleta', 'listarDadosAtleta');
$app->post('/listarDadosAtletas', 'listarDadosAtletas');

$app->get('/users', 'users');
$app->get('/user/:user', 'user');

$app->post('/insertCustomer', 'insertCustomer');
$app->post('/updateCustomer', 'updateCustomer');
$app->delete('/deleteCustomer/:param', 'deleteCustomer');


//metodos de chamadas da apei Slim Framework fim
$app->run();// start api

//testes inicio
function testes(){
    
    $password = hash('sha256','teste123');
    
    echo $password;
    
    //$password = hash('sha256',$data->password);
	/*$username = strtolower('teste@email.com');//converte tudo em minusculo
	
	$user = new Usuarios();
	$user->campos = 'id_usuario, nome, email, username, nivel_acesso';
	$user->extras_select = 'WHERE (username="'.$username.'" or email="'.$username.'") and password="'.$password.'"';
	$userData = $user->findAll($user);

    if(sizeof($userData) > 0){
	$user_id=$userData[0]['id_usuario'];
	$username=$userData[0]['username'];
	$userData[0]['token'] = apiToken($user_id);
	
	date_default_timezone_set('America/Sao_Paulo');
	$datahora = date('Y-m-d H:i');

	$informacao = "Logado com sucesso!";
    	
    	$logacesso = new LogAcesso();
    	$logacesso->setValor('id_usuario',$user_id);
    	$logacesso->setValor('usuario',$username);
    	$logacesso->setValor('datahora',$datahora);
    	$logacesso->setValor('informacao',$informacao);
    	$logacesso->insert($logacesso);
    }else{
    	date_default_timezone_set('America/Sao_Paulo');
	$datahora = date('Y-m-d H:i');

	$informacao = "Tentativa de Acesso/Login!";
    	
    	$logacesso = new LogAcesso();
    	$logacesso->setValor('id_usuario','');
    	$logacesso->setValor('usuario',$data->username);
    	$logacesso->setValor('datahora',$datahora);
    	$logacesso->setValor('informacao',$informacao);
    	$logacesso->insert($logacesso);
    }

    if(sizeof($userData) > 0){
    	$userData = retiraCaracteres(json_encode($userData));
	echo '{"userData": ' .$userData . '}';
    } else {
    	echo '{"error":{"text":"Dados incorretos!"}}';
    }

    $list = new ControleFisiologicoPP();
		//$list->campos = 'id_controle_fisiologico, id_usuario, peso_pre, peso_pos, fad, qs, dm, data_hora, periodo, informacoes_adicionais';
		
		$resultado = $list->findAll($list);
		
	///print_r($resultado);
	
	*/
	
	/*foreach ($resultado as $value):
            //
            foreach ($value as $saida):
                 echo $saida;
            endforeach;
            //
        endforeach;*/
		
		//echo json_encode($result);
		//print_r($result);
		//echo retiraCaracteres(json_encode($result));
	
	//echo '{"msg":"Testando!"}';
	/*
	$user = new Usuarios();
	$user->campos = 'id_usuario, nome, email, foto, username';
	$user->extras_select = 'WHERE id_usuario="1"';
	$result = $user->findAll($user);
	
	print_r($result[0]['nome']);
        */
        
}
//testes fim

function login() {
    
    echo '{"error":{"text":"Dados incorretos!"}}';
    
    /*
    $request = \Slim\Slim::getInstance()->request();
	$data = json_decode($request->getBody(),true);
	
	$password = hash('sha256',$data['password']);
	$username = strtolower(['$data->username']);//converte tudo em minusculo
	
	$user = new Usuarios();
	$user->campos = 'id_usuario, nome, email, username, nivel_acesso';
	$user->extras_select = 'WHERE (username="'.$username.'" or email="'.$username.'") and password="'.$password.'"';
	$userData = $user->findAll($user);

    if(sizeof($userData) > 0){
	$user_id=$userData[0]['id_usuario'];
	$username=$userData[0]['username'];
	$userData[0]['token'] = apiToken($user_id);
	
	date_default_timezone_set('America/Sao_Paulo');
	$datahora = date('Y-m-d H:i');

	$informacao = "Logado com sucesso!";
    	
    	$logacesso = new LogAcesso();
    	$logacesso->setValor('id_usuario',$user_id);
    	$logacesso->setValor('usuario',$username);
    	$logacesso->setValor('datahora',$datahora);
    	$logacesso->setValor('informacao',$informacao);
    	$logacesso->insert($logacesso);
    }else{
    	date_default_timezone_set('America/Sao_Paulo');
	$datahora = date('Y-m-d H:i');

	$informacao = "Tentativa de Acesso/Login!";
    	
    	$logacesso = new LogAcesso();
    	$logacesso->setValor('id_usuario','');
    	$logacesso->setValor('usuario',$data->username);
    	$logacesso->setValor('datahora',$datahora);
    	$logacesso->setValor('informacao',$informacao);
    	$logacesso->insert($logacesso);
    }

    if(sizeof($userData) > 0){
    	$userData = retiraCaracteres(json_encode($userData));
	echo '{"userData": ' .$userData . '}';
    } else {
    	echo '{"error":{"text":"Dados incorretos!"}}';
    }
    */

}

function signup() {
	
	$request = \Slim\Slim::getInstance()->request();
	$data = json_decode($request->getBody());
	
	//echo json_encode($data);
	
    $email=strtolower($data->email);//converte tudo em minuscula
    $nome=$data->nome;
    $username=strtolower($data->username);//converte tudo em minuscula
    $password=$data->password;
    
    $username_check = preg_match('~^[A-Za-z0-9_]{3,20}$~i', $username);
    $emain_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
    $password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password);

    if (strlen(trim($password))>0 && strlen(trim($email))>0 && $emain_check>0 && $password_check>0){
    
		$user = new Usuarios();
		$user->campos = 'id_usuario, nome, email, username';
		$user->extras_select = 'WHERE username="'.$username.'" or email="'.$email.'"';
		$result = $user->findAll($user);
		
		//echo json_encode(sizeof($result));

		if(sizeof($result) == 0){
			
			//$password=hash('sha256',$data->password);
			$password=hash('sha256','Dragao*123');
			
			$datahora = explode('T', $data->datahora);
			$data_hora = substr($datahora[0].' '.$datahora[1],0,19);
			
			$useradd = new Usuarios();
			
			$useradd->setValor('nome',$nome);
			$useradd->setValor('email',$email);
			$useradd->setValor('username',$username);
			$useradd->setValor('password',$password);
			$useradd->setValor('datahora',$data_hora);
			$useradd->setValor('nivel_acesso',1);//1 padrao usuario atleta, 0 usuario treinador
			
			$result = $useradd->insert($useradd);
			//echo json_encode($result);

			if($result > 0){
				$userData[0]['id_usuario'] = $result;
				$userData[0]['token'] = apiToken($result);
				$userData[0]['nome'] = $nome;
				$userData[0]['email'] = $email;
				$userData[0]['username'] = $username;
				$userData = retiraCaracteres(json_encode($userData));
				echo '{"userData": ' .$userData . '}';
			}else{
				echo '{"error":{"text":"Erro ao cadastrar!"}}';
			}

			//echo json_encode($result);

		}else{
			echo '{"error":{"text":"Email já cadastrado!"}}';
		}
	
	}else{
		echo '{"error":{"text":"Email incompleto ou password muito curto!"}}';
	}
    
    //echo json_encode(strlen(trim($password_check)));
    		
}


function dados_usuario(){
	$request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
    
		//echo json_encode($data);
		$user = new Usuarios();
		$user->campos = 'id_usuario, nome, email, foto, username, cpf';
		$user->extras_select = 'WHERE id_usuario="'.$id_usuario.'"';
		$result = $user->findAll($user);
		
		if(sizeof($result) > 0){
			echo '{"userData": ' . json_encode($result) . '}';
		}else{
			echo '{"error":{"text":"Nenhum usuário cadastrado!"}}';
		}
		
		//echo '{"userData":'.$result['id_usuario'].'}';
		
		/*
		$userData[0]['id_usuario'] = $result[0]['id_usuario'];
		$userData[0]['nome'] = $result[0]['nome'];
		$userData[0]['foto'] = $result[0]['foto'];
		$userData[0]['email'] = $result[0]['email'];
		$userData[0]['username'] = $result[0]['username'];
		$userData = retiraCaracteres(json_encode($userData));
		
		echo json_encode($userData);
		*/
		//echo json_encode($result);
		
		/*
		if($result > 0){
			$userData[0]['id_usuario'] = $result->id_usuario;
			$userData[0]['token'] = apiToken($result);
			$userData[0]['nome'] = $nome;
			$userData[0]['email'] = $email;
			$userData[0]['username'] = $username;
			$userData[0]['email'] = $email;
			$userData = retiraCaracteres(json_encode($userData));
			echo '{"userData": ' .$userData . '}';
		}else{
			echo '{"error":{"text":"Erro ao cadastrar!"}}';
		}
		*/
		//if(sizeof($result) > 0){
			//echo '{"userData":"'.$userData.'"}';
		//}else{
			//echo '{"error":{"text":"Não foi possível cadastrar!"}}';
		//}
			
		//echo '{"userData":'.$userData.'}';
		//echo json_encode($result);
		//echo retiraCaracteres(json_encode($result));
	
	}else{
		echo '{"error":{"text":"Você não tem permissão!"}}';
	}
	
}

function alterar_dados_usuario() {
	$request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
		//echo json_encode($data);
		$upd = new UpdDadoUsuario();
		$upd->valorpk = $id_usuario;
		$upd->setValor('nome',$data->nome);
		$upd->setValor('email',strtolower($data->email));
		$upd->setValor('username',strtolower($data->username));
		$upd->setValor('cpf',$data->cpf);
		$result = $upd->update($upd);
		if($result === true){
			//echo json_encode($result);
			$userData[0]['id_usuario'] = $id_usuario;
			$userData[0]['token'] = apiToken($id_usuario);
			$userData[0]['nome'] = $data->nome;
			$userData[0]['email'] = strtolower($data->email);
			$userData[0]['username'] = strtolower($data->username);
			$userData = retiraCaracteres(json_encode($userData));
			echo '{"userData": ' .$userData . '}';
		}else{
			echo '{"error":{"text":"Erro ao tentar atualizar!"}}';
		}
	}else{
		echo '{"error":{"text":"Você não tem permissão!"}}';
	}
	
}

function alterar_senha_usuario() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
		$passwordold=hash('sha256',$data->passwordold);
		$password=hash('sha256',$data->password);
		//echo json_encode($data);
		$user = new Usuarios();
		$user->campos = 'id_usuario';
		$user->extras_select = 'WHERE (id_usuario="'.$id_usuario.'" AND password="'.$passwordold.'")';
		$result = $user->findAll($user);
		
		if(sizeof($result) > 0){
			//echo '{"userData": ' . json_encode($result) . '}';
			//echo json_encode($data);
			$upd = new UpdPasswordUsuario();
			$upd->valorpk = $id_usuario;
			$upd->setValor('password',$password);
			$result = $upd->update($upd);
			if($result === true){
				//echo json_encode($result);
				echo '{"userData": ' .$result . '}';
			}else{
				echo '{"error":{"text":"Erro ao tentar atualizar senha!"}}';
			}
		}else{
			echo '{"error":{"text":"'.$password.'"}}';
		}
		
	}else{
		echo '{"error":{"text":"Você não tem permissão!"}}';
	}
	
}

function alterar_foto_usuario() {
	$request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
		//echo json_encode($data);
		$upd = new UpdFotoUsuario();
		$upd->valorpk = $id_usuario;
		$upd->setValor('foto',$data->foto);
		$result = $upd->update($upd);
		if($result === true){
			//echo json_encode($result);
			echo '{"userData": ' .$result . '}';
		}else{
			echo '{"error":{"text":"Erro ao tentar atualizar a foto!"}}';
		}
	}else{
		echo '{"error":{"text":"Você não tem permissão!"}}';
	}
	
}

function listarAtletas(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
        //echo '{"userData": ' .json_encode($data). '}';
	$user = new Usuarios();
	$user->campos = 'id_usuario, nome, foto';
	$user->extras_select = 'WHERE nivel_acesso = 1 ORDER BY nome ASC';
	$result = $user->findAll($user);
		
	if(sizeof($result) > 0){
		echo '{"userData": ' . json_encode($result) . '}';
	}else{
		echo '{"error":{"text":"Nenhum usuário cadastrado!"}}';
	}
	
    }else{
        echo '{"error":{"text":"Você não tem permissão!"}}';
    }
	
}

function listarDadosAtleta(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
        //echo '{"userData": ' .json_encode($data). '}';
	$user = new Usuarios();
	//$user->campos = 'tbl_usuario.id_usuario, nome, foto, peso_pre, peso_pos, fad, qs, dm, data_hora, periodo, informacoes_adicionais';
	//$user->extras_select = 'INNER JOIN tbl_controle_fisiologico ON (tbl_usuario.id_usuario = tbl_controle_fisiologico.id_usuario) WHERE tbl_controle_fisiologico.id_usuario = "'.$data->idUser.'" ORDER BY data_hora DESC';
	$user->campos = 'tbl_usuario.id_usuario, nome, foto, peso, fad, qs, dm, pse, ccu, data_hora, periodo, pre_pos_treino, informacoes_adicionais';
	$user->extras_select = 'INNER JOIN tbl_controlefisiologico ON (tbl_usuario.id_usuario = tbl_controlefisiologico.id_usuario) WHERE tbl_controlefisiologico.id_usuario = "'.$data->idUser.'" ORDER BY data_hora DESC';
	$result = $user->findAll($user);
		
	if(sizeof($result) > 0){
		echo '{"userData": ' . json_encode($result) . '}';
	}else{
		echo '{"error":{"text":"Nenhum dado encontrado!"}}';
	}
	
    }else{
        echo '{"error":{"text":"Você não tem permissão!"}}';
    }
	
}

function listarDadosAtletas(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    //$AnoDiaMes = "2018-01-29";
    $AnoDiaMes = $data->ano_dia_mes;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
        //echo '{"userData": ' .json_encode($data). '}';
	$user = new Usuarios();
	/*
	$user->campos = 'tbl_usuario.id_usuario, nome, foto, peso_pre, peso_pos, fad, qs, dm, data_hora, periodo, informacoes_adicionais';
	$user->extras_select = 'INNER JOIN tbl_controle_fisiologico ON (tbl_usuario.id_usuario = tbl_controle_fisiologico.id_usuario) WHERE nivel_acesso = 1 AND date(data_hora) = "'.$AnoDiaMes.'" ORDER BY data_hora DESC';
	*/
	$user->campos = 'tbl_usuario.id_usuario, nome, foto, peso, fad, qs, dm, pse, ccu, data_hora, periodo, pre_pos_treino, informacoes_adicionais';
	$user->extras_select = 'INNER JOIN tbl_controlefisiologico ON (tbl_usuario.id_usuario = tbl_controlefisiologico.id_usuario) WHERE nivel_acesso = 1 AND date(data_hora) = "'.$AnoDiaMes.'" ORDER BY data_hora DESC';
	//$user->extras_select = 'INNER JOIN tbl_controle_fisiologico ON (tbl_usuario.id_usuario = tbl_controle_fisiologico.id_usuario) WHERE nivel_acesso = 1 ORDER BY data_hora DESC';
	$result = $user->findAll($user);
		
	if(sizeof($result) > 0){
		echo '{"userData": ' . json_encode($result) . '}';
	}else{
		echo '{"error":{"text":"Nenhum dado encontrado!"}}';
	}
	
    }else{
        echo '{"error":{"text":"Você não tem permissão!"}}';
    }
	
}

function listarDadosFadigaRecuperacao() {
	
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    
    $systemToken=apiToken($id_usuario);
    
    if($systemToken == $token){
		//echo json_encode($data);
		$list = new ControleFisiologicoPP();
		$list->campos = 'id_controlefisiologico, id_usuario, peso, fad, qs, dm, pse, ccu, data_hora, periodo, pre_pos_treino, informacoes_adicionais';
		$list->extras_select = 'WHERE id_usuario = "'.$id_usuario.'" ORDER BY data_hora DESC';
		$result = $list->findAll($list);
		//echo json_encode($result);
		//echo retiraCaracteres(json_encode($result));
		if(sizeof($result) > 0){
			echo '{"userData": ' .json_encode($result). '}';
		}else{
			echo '{"error":{"text":"Nenhum dado cadastrado!"}}';
		}
	}else{
	      echo '{"error":{"text":"Você não tem permissão!"}}';
	}
    
}

function cadastrarGerenciamentoFadigaRecuperacao() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $id_usuario=$data->id_usuario;
    $token=$data->token;
    $nome=$data->nome;
    $email=$data->email;
    $username=$data->username;
	
	$systemToken=apiToken($id_usuario);
	
	$datahora = explode('T', $data->data);
	$data_hora = substr($datahora[0].' '.$datahora[1],0,19);
	
	/*
	if($data->pre_pos == "Pre"){
		$peso_pre = $data->peso;
		$peso_pos = "";
	}else{
		$peso_pre = "";
		$peso_pos = $data->peso;
	}
	*/
	
	if($data->pre_pos == "Pre"){
		$pse = 11;
		$fad = $data->fad;
		$qs = $data->qs;
		$dm = $data->dm;
	}else{
		$pse = $data->pse;
		$fad = 11;
		$qs = 11;
		$dm = 11;
	}
	
	if($systemToken == $token){
		
		//echo json_encode($data);
	
		if(!empty($data->peso) or !empty($data->fad) or !empty($data->qs) or !empty($data->dm) or !empty($data->pse) or !empty($data->ccu)){
			
			/*
			$addcd = new ControleFisiologico();
				
			$addcd->setValor('id_usuario',$id_usuario);
			$addcd->setValor('peso_pre',$peso_pre);
			$addcd->setValor('peso_pos',$peso_pos);
			$addcd->setValor('fad',$data->fad);
			$addcd->setValor('qs',$data->qs);
			$addcd->setValor('dm',$data->dm);
			$addcd->setValor('data_hora',$data_hora);
			$addcd->setValor('periodo',$data->periodo);
			$addcd->setValor('informacoes_adicionais',$data->informacoes_adicionais);
			*/
			
			$addcd = new ControleFisiologicoPP();
			
			$addcd->setValor('id_usuario',$data->id_usuario);
			$addcd->setValor('peso',$data->peso);
			$addcd->setValor('fad',$fad);
			$addcd->setValor('qs',$qs);
			$addcd->setValor('dm',$dm);
			$addcd->setValor('pse',$pse);
			$addcd->setValor('ccu',$data->ccu);
			$addcd->setValor('data_hora',$data_hora);
			$addcd->setValor('periodo',$data->periodo);
			$addcd->setValor('pre_pos_treino',$data->pre_pos);
			$addcd->setValor('informacoes_adicionais',$data->informacoes_adicionais);

			$result = $addcd->insert($addcd);
			
			if($result > 0){
				echo '{"userData":"'.$result.'"}';
			}else{
				echo '{"error":{"text":"Não foi possível cadastrar!"}}';
			}
						
		}else{
			echo '{"error":{"text":"Dados incorretos!"}}';
		}
		
	}else{
		echo '{"error":{"text":"Você não tem permissão!"}}';
	}

}

//users inicio
function users(){
	$users = new Users();
	$users->campos = 'user_id, username, name, email';
	$users->extras_select = 'ORDER BY user_id DESC';
	$result = $users->findAll($users);
	echo json_encode($result);
}

function user($param){
	$user = new Users();
	$user->campos = 'user_id, username, name, email';
	$user->extras_select = 'WHERE user_id="'.$param.'"';
	$result = $user->findAll($user);
	echo retiraCaracteres(json_encode($result));
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

function logAcesso($user_id, $username, $informacao){
	$informacao = "Logado com sucesso!";
    	$datahora = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
    	
    	$logacesso = new LogAcesso();
    	$logacesso->setValor('id_usuario',$user_id);
    	$logacesso->setValor('usuario',$username);
    	$logacesso->setValor('datahora',$datahora);
    	$logacesso->setValor('informacao',$informacao);//1 padrao usuario atleta, 0 usuario treinador
			
    	$logacesso->insert($logacesso);
}

function retiraCaracteres($valor){
		$caracteres = array("[", "]");
		return str_replace($caracteres, "", $valor);
}
	
/* API key encryption */
function apiToken($session_uid) {
	$key=md5($SITE_KEY.$session_uid);
	return hash('sha256', $key);
}
