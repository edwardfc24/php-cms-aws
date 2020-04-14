<?php 
/**
* PaqueteServicio
*/
class PaqueteServicio
{
	public $isRelacion;
	public $idPaquete;
	public $idServicio;

	public function getIdRelacion() {
		return $this->isRelacion;
	}
	public function setIdRelacion($id) {
		$this->isRelacion = $id;
	}
	public function getIdPaquete() {
		return $this->idPaquete;
	}
	public function setIdPaquete($campana)
	{
		$this->idPaquete = $campana;
	}
	public function getIdServicio() {
		return $this->idServicio;
	}
	public function setIdServicio($servicio)
	{
		$this->idServicio = $servicio;
	}
}
?>