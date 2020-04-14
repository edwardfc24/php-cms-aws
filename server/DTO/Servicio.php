<?php  
/**
* Servicio
*/
class Servicio
{
	public $idServicio;
	public $txtNombre_es;
	public $txtNombre_pt;
	public $estado;
	public $orden;

	public function getIdServicio() {
		return $this->idServicio;
	}
	public function setIdServicio($id) {
		$this->idServicio = $id;
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
	public function getEstado() {
		return $this->estado;
	}
	public function setEstado($padre) {
		$this->estado = $padre;
	}
	public function getOrden() {
		return $this->orden;
	}
	public function setOrden($order) {
		$this->orden = $order;
	}
}
?>