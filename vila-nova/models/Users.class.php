<?php

class Users extends Modal {
    public function __construct($campos = array()) {
	$this->table = "users";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"user_id" => NULL,
				"username" => NULL,
				"password" => NULL,
				"name" => NULL,
				"email" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "user_id";
    }
}
