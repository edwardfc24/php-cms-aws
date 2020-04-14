<?php 

class PaqueteBLL {

	function selectAll() {
		$claseConexion = new Connection();
		$csql = "CALL sp_Paquetes_SelectAll()";
		$res = $claseConexion->query($csql);
		$lista = array(); 
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
			$objResultado = $this->rowToDto($row);
			$lista[] = $objResultado;
		}
		return $lista;
	}

	function selectById($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Paquetes_SelectById(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}


	function insert($name_es, $name_pt, $order, $state) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Paquetes_Insert(@idPaquete, :name_es, :name_pt, :order, :state)";
		$res = $claseConexion->queryWithParams($csql, array(
			':name_es' => $name_es,
			':name_pt' => $name_pt,
			':order' => $order,
			':state' => $state
			));
		$outputArray = $claseConexion->query("select @idPaquete")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idPaquete'];
	}

	function update($id, $name_es, $name_pt, $order, $state) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Paquetes_Update(:id, :name_es, :name_pt, :order, :state)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id,
			':name_es' => $name_es,
			':name_pt' => $name_pt,
			':order' => $order,
			':state' => $state
			));
	}
	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Paquetes_Delete(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function rowToDto($row) {
		$paquete = new Paquete();
		$paquete->setIdPaquete($row['idPaquete']);
		$paquete->setTxtNombreEs($row['txtNombre_es']);
		$paquete->setTxtNombrePt($row['txtNombre_pt']);
		$paquete->setEstado($row['estado']);
		$paquete->setOrden($row['orden']);
		return $paquete;
	}
}
?>