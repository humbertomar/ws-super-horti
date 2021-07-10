<?php

abstract class Modal extends DAO{
	
	public $table = NULL;
	public $campos_valores = array();
	public $campos = array();
	public $campopk = NULL;
	public $valorpk = NULL;
	public $extras_select = NULL;
	
	public function addCampo($campo=NULL,$valor=NULL){
		if($campo!=NULL):
			$this->campos_valores[$campo] = $valor;
		endif;
	}
	
	public function delCampo($campo=NULL){
		if(array_key_exists($campo,$this->campos_valores)):
			unset($this->campos_valores[$campo]);
		endif;
	}
	
	public function setValor($campo=NULL,$valor=NULL){
		if($campo!=NULL && $valor!=NULL):
			$this->campos_valores[$campo] = $valor;
		endif;
	}
	
	public function getValor($campo=NULL){
		if($campo!=NULL && array_key_exists($campo,$this->campos_valores)):
			return $this->campos_valores[$campo];
		else:
			return FALSE;
		endif;
	}
	
}
