<?php  
/**
* Propiedad
*/
class Propiedad 
{
	public $idPropiedad;
	public $txtNombre;
	public $latitud;
	public $longitud;
	public $idCliente;

	public function getIdPropiedad() {
		return $this->idPropiedad;
	}
	public function setIdPropiedad($id) {
		$this->idPropiedad = $id;
	}
	public function getTxtNombre() {
		return $this->txtNombre;
	}
	public function setTxtNombre($nombre) {
		$this->txtNombre = $nombre;
	}
	public function getLatitud() {
		return $this->latitud;
	}
	public function setLatitud($lat) {
		$this->latitud = $lat;
	}
	public function getLongitud() {
		return $this->longitud;
	}
	public function setLongitud($long) {
		$this->longitud = $long;
	}
	public function getIdCliente() {
		return $this->idCliente;
	}
	public function setIdCliente($cliente) {
		$this->idCliente = $cliente;
	}
}
?>