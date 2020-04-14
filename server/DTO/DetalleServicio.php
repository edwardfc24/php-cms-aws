<?php  
/**
* Servicio
*/
class DetalleServicio
{
	public $idDetalleServicio;
	public $txtNombre_es;
	public $txtNombre_pt;
	public $estado;
	public $idServicio;
	public $orden;

	public function getIdDetalleServicio() {
		return $this->idDetalleServicio;
	}
	public function setIdDetalleServicio($id) {
		$this->idDetalleServicio = $id;
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
	public function setTxtNombrePt($nombre) {
		$this->txtNombre_pt = $nombre;
	}
	public function getEstado() {
		return $this->estado;
	}
	public function setEstado($padre) {
		$this->estado = $padre;
	}
	public function getIdServicio(){
		return $this->idServicio;
	}
	public function setIdServicio($idServicio){
		$this->idServicio = $idServicio;
	}
	public function getOrden() {
		return $this->orden;
	}
	public function setOrden($order) {
		$this->orden = $order;
	}
}
?>