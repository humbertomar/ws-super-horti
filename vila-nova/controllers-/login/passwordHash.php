<?php

class passwordHash {

    // blowfish
    private static $algo = '$2a';
    // cost parameter
    private static $cost = '$10';

    // mainly for internal use
    public static function unique_salt() {
        return substr(sha1(mt_rand()), 0, 22);
    }

    // this will be used to generate a hash
    public static function hash($password) {
        return crypt($password, self::$algo.self::$cost.'$'. self::unique_salt());
    }

    // this will be used to compare a password against a hash
    public static function check_password($hash, $password) {
        $full_salt = substr($hash, 0, 29);
        $new_hash = crypt($password, $full_salt);
        return ($hash == $new_hash);
    }

}

//
class passwordOpenCard {
 
	//gerar o salt
    public static function salt() {
		$cryptMD5 = md5(uniqid(rand(), true));
		$salt = substr($cryptMD5, 0, 9);
        return ($salt);
    }
 
	//gera o password
	public static function passwordSalt($salt,$password) {
        $pass = sha1($salt.sha1($salt.sha1($password)));
        return $pass;
    }
 
    //este sera usado para comparar uma senha contra um salt
    public static function check_password_opencart($passDB,$salt,$password) {//pass banco, salt banco, pass formulario
		#criptografia do opencart
		$passLogin = sha1($salt.sha1($salt.sha1($password)));//pass formulario
        return ($passDB == $passLogin);
    }

}
//

?>
