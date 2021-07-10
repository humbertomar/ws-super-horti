<?php

class Usuarios extends Modal {
    public function __construct($campos = array()) {
	$this->table = "tbl_usuario";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"id_usuario" => NULL,
				"nome" => NULL,
				"foto" => NULL,
				"email" => NULL,
				"cpf" => NULL,
				"username" => NULL,
				"password" => NULL,
				"recover_password" => NULL,
				"datahora" => NULL,
				"nivel_acesso" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id_usuario";
    }
}

class UpdDadoUsuario extends Modal {
    public function __construct($campos = array()) {
	$this->table = "tbl_usuario";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"id_usuario" => NULL,
				"nome" => NULL,
				"email" => NULL,
				"cpf" => NULL,
				"username" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id_usuario";
    }
}

class UpdPasswordUsuario extends Modal {
    public function __construct($campos = array()) {
	$this->table = "tbl_usuario";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"id_usuario" => NULL,
				"password" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id_usuario";
    }
}

class UpdFotoUsuario extends Modal {
    public function __construct($campos = array()) {
	$this->table = "tbl_usuario";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"id_usuario" => NULL,
				"foto" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id_usuario";
    }
}
