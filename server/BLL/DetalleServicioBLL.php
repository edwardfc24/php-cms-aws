<?php 

class DetalleServicioBLL {

	function selectAll() {
		$claseConexion = new Connection();
		$csql = "CALL sp_DetalleServicios_SelectAll()";
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
		$csql = "CALL sp_DetalleServicios_SelectById(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
		));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}

	function selectByServiceId($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_DetalleServicios_SelectByServicioId(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
		));
		$lista = array(); 
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
			$objResultado = $this->rowToDto($row);
			$lista[] = $objResultado;
		}
		return $lista;
	}


	function insert($name_es, $name_pt, $state, $service, $order) {
		$claseConexion = new Connection();
		$csql = "CALL sp_DetalleServicios_Insert(@idDetalleServicio, :name_es, :name_pt, :state, :service, :order)";
		$res = $claseConexion->queryWithParams($csql, array(
			':name_es' => $name_es,
			':name_pt' => $name_pt,
			':state' => $state,
			':service' => $service,
			':order' => $order,
		));
		$outputArray = $claseConexion->query("select @idDetalleServicio")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idDetalleServicio'];
	}

	function update($id, $name_es, $name_pt, $state, $service, $order) {
		$claseConexion = new Connection();
		$csql = "CALL sp_DetalleServicios_Update(:id, :name_es, :name_pt, :state, :service, :order)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id,
			':name_es' => $name_es,
			':name_pt' => $name_pt,
			':state' => $state,
			':service' => $service,
			':order' => $order,
		));
	}

	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_DetalleServicios_Delete(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
		));
	}

	function rowToDto($row) {
		$detalle = new DetalleServicio();
		$detalle->setIdDetalleServicio($row['idDetalleServicio']);
		$detalle->setTxtNombreEs($row['txtNombre_es']);
		$detalle->setTxtNombrePt($row['txtNombre_pt']);
		$detalle->setEstado($row['estado']);
		$detalle->setIdServicio($row['idServicio']);
		$detalle->setOrden($row['orden']);
		return $detalle;
	}
}
?>