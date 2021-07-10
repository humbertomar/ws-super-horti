<?php

class Products_ extends Modal {
    public function __construct($campos = array()) {
        $this->table = "products_";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
				"category_id" => NULL,
				"images" => NULL,
				"name" => NULL,
				"price" => NULL,
				"rating" => NULL,
				"sale_price" => NULL,
				"short_description" => NULL,
				"thumb" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}

class Categories_ extends Modal {
	public function __construct($campos = array()) {
        $this->table = "categories_";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
				"name" => NULL,
				"thumb" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}

class Promotions extends Modal {
	public function __construct($campos = array()) {
        $this->table = "promotions";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
				"thumb" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}
