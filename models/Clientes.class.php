<?php

class Clientes extends Modal {
    public function __construct($campos = array()) {
        $this->table = "clientes";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
                "name" => NULL,
                "email" => NULL,
                "mobileNo" => NULL,
                "userId" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}