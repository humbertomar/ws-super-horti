<?php

class Funcoes{
	
	public function retiraCaracteres($valor){
		$caracteres = array("[", "]");
		return str_replace($caracteres, "", $valor);
	}
	
}

