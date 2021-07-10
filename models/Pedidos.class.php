<?php

class Pedidos extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedidos";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"pedido_id" => NULL,
                "orderId" => NULL,
                "grandTotal" => NULL,
                "subTotal" => NULL,
                "tax" => NULL,
                "tipo_pagamento" => NULL,
                "troco" => NULL,
                "email" => NULL,
                "name" => NULL,
                "userid" => NULL,
                "mobileNo" => NULL,
                "shippingAddress" => NULL,
                "observacoes" => NULL,
                "createdAt" => NULL,
                "pedido_agendado" => NULL,
				"data_entrega" => NULL,
				"log_pedidos" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "pedido_id";
    }
}

class CancelPedido extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedidos";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"pedido_id" => NULL,
                "data_cancel_entrega" => NULL,
                "status_pedido" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "pedido_id";
    }
}

class TicketPedido extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedidos";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"pedido_id" => NULL,
                "ticket" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "pedido_id";
    }
}

class PedidoItems extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedido_items";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
                "pedido_id" => NULL,
                "orderId" => NULL,
                "itemId" => NULL,
                "name" => NULL,
                "price" => NULL,
                "image" => NULL,
                "itemQunatity" => NULL,
                "itemTotalPrice" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}

class PedidoItems2 extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedido_items2";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
                "pedido_id" => NULL,
                "orderId" => NULL,
                "itemId" => NULL,
                "name" => NULL,
                "price" => NULL,
                "image" => NULL,
                "itemQunatity" => NULL,
                "itemTotalPrice" => NULL,
                "pedido_id_r" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "id";
    }
}

class DeletePedidoItems2 extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedido_items2";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"id" => NULL,
                "status" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "pedido_id";
    }
}

class UpdPedido extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedidos";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"pedido_id" => NULL,
                "status_pagamento" => NULL,
                "status_pedido" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "pedido_id";
    }
}

class DeletePedido extends Modal {
    public function __construct($campos = array()) {
        $this->table = "pedidos";
        if (sizeof($campos) <= 0):
            $this->campos_valores = array(
                //"pedido_id" => NULL,
                "status" => NULL
            );
        else:
            $this->campos_valores = $campos;
        endif;
        $this->campopk = "pedido_id";
    }
}
