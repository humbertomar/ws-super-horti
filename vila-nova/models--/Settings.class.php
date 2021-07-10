<?php

class Settings extends Modal {
    public function __construct($campos = array()) {
        $this->table = "settings";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
                "qtdade_pedidos_dia" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}