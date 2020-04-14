<?php 

class ServicioBLL {

	function selectAll() {
		$claseConexion = new Connection();
		$csql = "CALL sp_Servicios_SelectAll()";
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
		$csql = "CALL sp_Servicios_SelectById(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}


	function insert($name_es, $name_pt, $state, $order) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Servicios_Insert(@idServicio, :name_es, :name_pt, :state, :order)";
		$res = $claseConexion->queryWithParams($csql, array(
			':name_es' => $name_es,
			':name_pt' => $name_pt,
			':state' => $state,
			':order' => $order
			));
		$outputArray = $claseConexion->query("select @idServicio")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idServicio'];
	}

	function update($id, $name_es, $name_pt, $state, $order) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Servicios_Update(:id, :name_es, :name_pt, :state, :order)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id,
			':name_es' => $name_es,
			':name_pt' => $name_pt,
			':state' => $state,
			':order' => $order
			));
	}
	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Servicios_Delete(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function rowToDto($row) {
		$servicio = new Servicio();
		$servicio->setIdServicio($row['idServicio']);
		$servicio->setTxtNombreEs($row['txtNombre_es']);
		$servicio->setTxtNombrePt($row['txtNombre_pt']);
		$servicio->setEstado($row['estado']);
		$servicio->setOrden($row['orden']);
		return $servicio;
	}
}
?>