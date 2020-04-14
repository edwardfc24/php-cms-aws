<?php 

class PaqueteServicioBLL {

	function selectByPaqueteId($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_PaqueteServicios_SelectByPaqueteId(:id)";
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

	function insert($package, $service) {
		$claseConexion = new Connection();
		$csql = "CALL sp_PaqueteServicios_Insert(@idPaqueteServicio, :package, :service)";
		$res = $claseConexion->queryWithParams($csql, array(
			':package' => $package,
			':service' => $service,
			));
		$outputArray = $claseConexion->query("select @idPaqueteServicio")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idPaqueteServicio'];
	}


	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_PaqueteServicios_DeleteByPaqueteId(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function rowToDto($row) {
		$relacion = new PaqueteServicio();
		$relacion->setIdRelacion($row['idRelacion']);
		$relacion->setIdPaquete($row['idPaquete']);
		$relacion->setIdServicio($row['idServicio']);
		return $relacion;
	}

}
?>