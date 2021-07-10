<?php

abstract class DAO extends DB {

	public function insert($objeto){
		$sql = "INSERT INTO ".$objeto->table." (";
		for($i=0; $i<count($objeto->campos_valores); $i++):
			$sql .= key($objeto->campos_valores);
			if($i< (count($objeto->campos_valores)-1)):
				$sql .= ", ";
			else:
				$sql .= ") ";
			endif;
			next($objeto->campos_valores);
		endfor;
		reset($objeto->campos_valores);
		$sql .= "VALUES (";
		for($i=0; $i<count($objeto->campos_valores); $i++):
			$sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ?
				$objeto->campos_valores[key($objeto->campos_valores)] :
				"'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
			
			if($i< (count($objeto->campos_valores)-1)):
				$sql .= ", ";
			else:
				$sql .= ") ";
			endif;
			next($objeto->campos_valores);
		endfor;
		try {
			$stmt = DB::prepare($sql);
			$stmt->execute();
			$ultimoIdCad = DB::lastInsertId();
			return $ultimoIdCad;
		} catch(PDOException $e) {
			return '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function update($objeto){
		$sql = "UPDATE ".$objeto->table." SET ";
		for($i=0; $i<count($objeto->campos_valores); $i++):
			$sql .= key($objeto->campos_valores)."=";
				$sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ?
				$objeto->campos_valores[key($objeto->campos_valores)] :
				"'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
			if($i< (count($objeto->campos_valores)-1)):
				$sql .= ", ";
			else:
				$sql .= " ";
			endif;
			next($objeto->campos_valores);
		endfor;
		$sql .= "WHERE ".$objeto->campopk."=";
		$sql .= is_numeric($objeto->valorpk) ? $objeto->valorpk : "'".$objeto->valorpk."'";
		try {
			$stmt = DB::prepare($sql);
			return $stmt->execute();
		} catch(PDOException $e) {
			return '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	
	public function updateAll($objeto){
		$sql = "UPDATE ".$objeto->table." SET ";
		for($i=0; $i<count($objeto->campos_valores); $i++):
			$sql .= key($objeto->campos_valores)."=";
				$sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ?

				$objeto->campos_valores[key($objeto->campos_valores)] :
				"'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
			if($i< (count($objeto->campos_valores)-1)):
				$sql .= ", ";
			else:
				$sql .= " ";
			endif;
			next($objeto->campos_valores);
		endfor;
		if($objeto->extras_select!=NULL):
			$sql .= " ".$objeto->extras_select;
		endif;
		try {
			$stmt = DB::prepare($sql);
			return $stmt->execute();
		} catch(PDOException $e) {
			return '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	public function findAll($objeto){
		$sql = "SELECT ";
			if($objeto->campos!=NULL):
				$sql .= $objeto->campos;
			else:
				$sql .= "*";
			endif;
		$sql .= " FROM ".$objeto->table;
			if($objeto->extras_select!=NULL):
				$sql .= " ".$objeto->extras_select;
			endif;
        try {
			$stmt = DB::prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll();
		} catch(PDOException $e) {
			return '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	
	public function findAllAssoc($objeto){
		$sql = "SELECT ";
			if($objeto->campos!=NULL):
				$sql .= $objeto->campos;
			else:
				$sql .= "*";
			endif;
		$sql .= " FROM ".$objeto->table;
			if($objeto->extras_select!=NULL):
				$sql .= " ".$objeto->extras_select;
			endif;
		try {
			$stmt = DB::prepare($sql);
			$stmt->execute();
        	return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
   
	public function findOne($objeto) {
        $sql = "SELECT ";
			if($objeto->campos!=NULL):
				$sql .= $objeto->campos;
			else:
				$sql .= "*";
			endif;
		$sql .= " FROM ".$objeto->table;
			if($objeto->extras_select!=NULL):
				$sql .= " ".$objeto->extras_select;
			endif;
        try {
			$stmt = DB::prepare($sql);
			$stmt->execute();
			return $stmt->fetch();
		} catch(PDOException $e) {
			return '{"error":{"text":'. $e->getMessage() .'}}';
		} 
    }
    
    public function delete($objeto){
		$sql = "DELETE FROM ".$objeto->table;
		$sql .= " WHERE ".$objeto->campopk."=";
		$sql .= is_numeric($objeto->valorpk) ? $objeto->valorpk : "'".$objeto->valorpk."'";
		try {
			$stmt = DB::prepare($sql);
			$stmt->execute();
			return $stmt->rowCount();
		} catch(PDOException $e) {
			return '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	
}
