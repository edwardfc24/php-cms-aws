<?php
/**
  * Usuario
  */
class Usuario
{
	public $idUsuario;
	public $txtNombre;
	public $txtApellidos;
	public $txtEmail;
	public $txtUser;
	public $txtPassword;
	public $estado;

	public function getIdUsuario() {
		return $this->idUsuario;
	}
	public function setIdUsuario($id) {
		$this->idUsuario = $id;
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
	public function getEstado() {
		return $this->estado;
	}
	public function setEstado($state) {
		$this->estado = $state;
	}
}  
?>