<?php 
/**
* Paquete
*/
class Paquete
{
	public $idPaquete;
	public $txtNombre_es;
	public $txtNombre_pt;
	public $orden;
	public $estado;

	public function getIdPaquete() {
		return $this->idPaquete;
	}
	public function setIdPaquete($id) {
		$this->idPaquete = $id;
	}
	public function getTxtNombreEs() {
		return $this->txtNombre_es;
	}
	public function setTxtNombreEs($nombre) {
		$this->txtNombre_es = $nombre;
	}
	public function getTxtNombrePt() {
		return $this->txtNombre_pt;
	}
	public function setTxtNombrePt($name) {
		$this->txtNombre_pt = $name;
	}
	public function getOrden() {
		return $this->orden;
	}
	public function setOrden($order) {
		$this->orden = $order;
	}
	public function getEstado() {
		return $this->estado;
	}
	public function setEstado($padre) {
		$this->estado = $padre;
	}
}
?>