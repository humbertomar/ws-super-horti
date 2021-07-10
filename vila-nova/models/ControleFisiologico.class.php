<?php

class ControleFisiologico extends Modal {
    public function __construct($campos = array()) {
	$this->table = "tbl_controle_fisiologico";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"id_controle_fisiologico" => NULL,
				"id_usuario" => NULL,
				"qu_pre" => NULL,
				"qu_pos" => NULL,
				"peso_pre" => NULL,
				"peso_pos" => NULL,
				"fad" => NULL,
				"qs" => NULL,
				"dm" => NULL,
				"pse" => NULL,
				"data_hora" => NULL,
				"periodo" => NULL,
				"informacoes_adicionais" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id_controle_fisiologico";
    }
}

class ControleFisiologicoPP extends Modal {
    public function __construct($campos = array()) {
	$this->table = "tbl_controlefisiologico";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
		//"id_controlefisiologico" => NULL,
		"id_usuario" => NULL,
		"peso" => NULL,
		"fad" => NULL,
		"qs" => NULL,
		"dm" => NULL,
		"pse" => NULL,
		"ccu" => NULL,
		"data_hora" => NULL,
		"periodo" => NULL,
		"pre_pos_treino" => NULL,
		"informacoes_adicionais" => NULL
	    );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id_controlefisiologico";
    }
}


