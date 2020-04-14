<?php 

class CampanaBLL {

	function selectAll() {
		$claseConexion = new Connection();
		$csql = "CALL sp_Campanas_SelectAll()";
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
		$csql = "CALL sp_Campanas_SelectById(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}

	function selectByPropertyId($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Campanas_SelectByPropiedadId(:id)";
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

	function insert($type, $gestion, $studyDate, $property, $package) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Campanas_Insert(@idCampana, :type, :gestion, :studyDate, :property, :package)";
		$res = $claseConexion->queryWithParams($csql, array(
			':type' => $type,
			':gestion' => $gestion,
			':studyDate' => $studyDate,
			':property' => $property,
			':package' => $package
			));
		$outputArray = $claseConexion->query("select @idCampana")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idCampana'];
	}

	function update($id, $type, $gestion, $studyDate, $property, $package) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Campanas_Update(:id, :type, :gestion, :studyDate, :property, :package)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id,
			':type' => $type,
			':gestion' => $gestion,
			':studyDate' => $studyDate,
			':property' => $property,
			':package' => $package
			));
	}

	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Campanas_Delete(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function rowToDto($row) {
		$campana = new Campana();
		$campana->setIdCampana($row['idCampana']);
		$campana->setTipoCampana($row['tipoCampana']);
		$campana->setGestion($row['intGestion']);
		$campana->setFechaEstudio($row['fechaEstudio']);
		$campana->setIdPropiedad($row['idPropiedad']);
		$campana->setIdPaquete($row['idPaquete']);
		return $campana;
	}

}
?>