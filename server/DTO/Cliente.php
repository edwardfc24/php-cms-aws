<?php  
/**
* Cliente
*/
class Cliente
{
	public $idCliente;
	public $txtNombre;
	public $txtApellidos;
	public $txtTelefono;
	public $txtEmail;
	public $txtUser;
	public $txtPassword;

	public function getIdCliente() {
		return $this->idCliente;
	}
	public function setIdCliente($id) {
		$this->idCliente = $id;
	}
	public function getTxtNombre() {
		return $this->txtNombre;
	}
	public function setTxtNombre($nombre) {
		$this->txtNombre = $nombre;
	}
	public function getTxtApellidos() {
		return $this->txtApellidos;
	}
	public function setTxtApellidos($apellidos) {
		$this->txtApellidos = $apellidos;
	}
	public function getTxtTelefono() {
		return $this->txtTelefono;
	}
	public function setTxtTelefono($phone) {
		$this->txtTelefono = $phone;
	}
	public function getTxtEmail() {
		return $this->txtEmail;
	}
	public function setTxtEmail($email) {
		$this->txtEmail = $email;
	}
	public function getTxtUser() {
		return $this->txtUser;
	}
	public function setTxtUser($user) {
		$this->txtUser = $user;
	}
	public function getTxtPassword() {
		return $this->txtPassword;
	}
	public function setTxtPassword($pass) {
		$this->txtPassword = $pass;
	}
}
?>