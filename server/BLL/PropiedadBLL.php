<?php 

class PropiedadBLL {

	function selectAll() {
		$claseConexion = new Connection();
		$csql = "CALL sp_Propiedades_SelectAll()";
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
		$csql = "CALL sp_Propiedades_SelectById(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}

	function selectByClientId($cliente) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Propiedades_SelectByClienteId(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $cliente
			));
		$lista = array(); 
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
			$objResultado = $this->rowToDto($row);
			$lista[] = $objResultado;
		}
		return $lista;
	}

	function insert($name, $lat, $long, $cliente) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Propiedades_Insert(@idPropiedad, :name, :lat, :long, :cliente)";
		$res = $claseConexion->queryWithParams($csql, array(
			':name' => $name,
			':lat' => $lat,
			':long' => $long,
			':cliente' => $cliente,
			));
		$outputArray = $claseConexion->query("select @idPropiedad")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idPropiedad'];
	}

	function update($id, $name, $lat, $long, $cliente) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Propiedades_Update(:id, :name, :lat, :long, :cliente)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id,
			':name' => $name,
			':lat' => $lat,
			':long' => $long,
			':cliente' => $cliente,
			));
		//$row = $res->fetch(PDO::FETCH_ASSOC);
	}

	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Propiedades_Delete(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function rowToDto($row) {
		$propiedad = new Propiedad();
		$propiedad->setIdPropiedad($row['idPropiedad']);
		$propiedad->setTxtNombre($row['txtNombre']);
		$propiedad->setLatitud($row['latitud']);
		$propiedad->setLongitud($row['longitud']);
		$propiedad->setIdCliente($row['idCliente']);
		return $propiedad;
	}
}
?>