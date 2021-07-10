<?php

$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["customer_id"] = $session['customer_id'];
    $response["email"] = $session['email'];
    $response["firstname"] = $session['firstname'];
    $response["lastname"] = $session['lastname'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->customer);
    $response = array();
    $db = new DbHandler();
    $password = $r->customer->password;
    $email = $r->customer->email;
    $user = $db->getOneRecord("select customer_id,firstname,lastname,password,salt,email from oc_customer where email='$email'");
    if ($user != NULL) {
        
        //if(passwordHash::check_password($user['password'],$user['salt'],$password)){
		if(passwordOpenCard::check_password_opencart($user['password'],$user['salt'],$password)){
        
        $response['status'] = "success";
        $response['message'] = 'Logado com sucesso.';
        $response['firstname'] = $user['firstname'];
        $response['lastname'] = $user['lastname'];
        $response['customer_id'] = $user['customer_id'];
        $response['email'] = $user['email'];
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['customer_id'] = $user['customer_id'];
        $_SESSION['email'] = $email;
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        } else {
            $response['status'] = "error";
            $response['message'] = 'Login Falou. Dados Incorretos';
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'Nenhum usuario cadastrado com esses dados';
        }
    echoResponse(200, $response);
});

$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'firstname', 'lastname', 'telephone', 'password'),$r->customer);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $telephone = $r->customer->telephone;
    $firstname = $r->customer->firstname;
    $lastname = $r->customer->lastname;
    $email = $r->customer->email;
    $password = $r->customer->password;
	//'{"customer":{"email":"email@email.com","firstname":"Firstname","lastname":"Lastname","telephone":"62988776655","password":"password123","salt":"salt123"}}'
    
    $isUserExists = $db->getOneRecord("select 1 from oc_customer where email='$email'");
    
    if(!$isUserExists){
        
		$salt = passwordOpenCard::salt();//aqui crio o salt
		$r->customer->password = passwordOpenCard::passwordSalt($salt,$password);//aqui crio o password e o coloco  no array customer
		$r->customer->salt = $salt;//aqui coloco o salt no array salt
		
		$tabble_name = "oc_customer";
        $column_names = array('telephone', 'firstname', 'lastname', 'email', 'password', 'salt');
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Usuario criado com sucesso!";
            $response["customer_id"] = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['customer_id'] = $response["customer_id"];
            $_SESSION['telephone'] = $telephone;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $email;
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Falha ao criar usuario. Por favor, tente novamente!";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "Existe um utilizador com o telefone ou e-mail fornecido!";
        echoResponse(201, $response);
    }
});

$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Desconectado com sucesso!";
    echoResponse(200, $response);
});

?>
