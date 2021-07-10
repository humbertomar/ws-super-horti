<?php

class LogAcesso extends Modal {
    public function __construct($campos = array()) {
	$this->table = "tbl_logacesso";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"id_logacesso" => NULL,
				"id_usuario" => NULL,
				"usuario" => NULL,
				"datahora" => NULL,
				"informacao" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id_logacesso";
    }
}