<?php

class Feed extends Modal {
    public function __construct($campos = array()) {
	$this->table = "feed";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
				//"feed_id" => NULL,
				"feed" => NULL,
				"user_id_fk" => NULL,
				"created" => NULL
			);
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "feed_id";
    }
}
