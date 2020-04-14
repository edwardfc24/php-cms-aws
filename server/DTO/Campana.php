<?php 
/**
* Campana
*/
class Campana
{
	public $idCampana;
	public $tipoCampana;
	public $gestion;
	public $fechaEstudio;
	public $idPropiedad;
	public $idPaquete;

	public function getIdCampana() {
		return $this->idCampana;
	}
	public function setIdCampana($id) {
		$this->idCampana = $id;
	}
	public function getTipoCampana() {
		return $this->tipoCampana;
	}
	public function setTipoCampana($tipo) {
		$this->tipoCampana = $tipo;
	}
	public function getGestion() {
		return $this->gestion;
	}
	public function setGestion($gestion) {
		$this->gestion = $gestion;
	}
	public function getFechaEstudio()
	{
		return $this->fechaEstudio;
	}
	public function setFechaEstudio($fecha)
	{
		$this->fechaEstudio = $fecha;
	}
	public function getIdPropiedad() {
		return $this->idPropiedad;
	}
	public function setIdPropiedad($propiedad)
	{
		$this->idPropiedad = $propiedad;
	}
	public function getIdPaquete()
	{
		return $this->idPaquete;
	}
	public function setIdPaquete($paquete)
	{
		$this->idPaquete = $paquete;
	}
}
?>