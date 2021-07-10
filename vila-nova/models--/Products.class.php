<?php

class Sliders extends Modal {
	public function __construct($campos = array()) {
        $this->table = "sliders";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
                "thumb" => NULL,
		"name" => NULL,
		"text" => NULL,
		"status" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}

class Products extends Modal {
    public function __construct($campos = array()) {
        $this->table = "products";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
				"category_id" => NULL,
				"store_id" => NULL,
				"name" => NULL,
				"price" => NULL,
				"description" => NULL,
				"image" => NULL,
				"active" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}

class Category extends Modal {
	public function __construct($campos = array()) {
        $this->table = "category";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
				"name" => NULL,
				"active" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}
