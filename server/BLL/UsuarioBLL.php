<?php 


class UsuarioBLL {

	function login($user, $pass) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Usuarios_Login(:user, :pass)";
		$res = $claseConexion->queryWithParams($csql, array(
			':user' => $user,
			':pass' => $pass
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}

	function selectAll() {
		$claseConexion = new Connection();
		$csql = "CALL sp_Usuarios_SelectAll()";
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
		$csql = "CALL sp_Usuarios_SelectById(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}

	function selectByUser($user) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Usuarios_SelectByUser(:user)";
		$res = $claseConexion->queryWithParams($csql, array(
			':user' => $user
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function insert($name, $lastname, $email, $user, $pass) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Usuarios_Insert(@idUsuario, :name, :lastname, :email, :user,:pass)";
		$res = $claseConexion->queryWithParams($csql, array(
			':name' => $name,
			':lastname' => $lastname,
			':email' => $email,
			':user' => $user,
			':pass' => $pass
			));
		$outputArray = $claseConexion->query("select @idUsuario")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idUsuario'];
	}


	function update($id, $name, $lastname, $email, $user, $pass, $state) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Usuarios_Update(:id, :name, :lastname, :email, :user, :pass, :state)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id,
			':name' => $name,
			':lastname' => $lastname,
			':email' => $email,
			':user' => $user,
			':pass' => $pass,
			':state' => $state
			));	
	}

	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Usuarios_Delete(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function takeDown($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Usuarios_DarBaja(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function rowToDto($row) {
		$usuario = new Usuario();
		$usuario->setIdUsuario($row['idUsuario']);
		$usuario->setTxtNombre($row['txtNombre']);
		$usuario->setTxtApellidos($row['txtApellidos']);
		$usuario->setTxtEmail($row['txtEmail']);
		$usuario->setTxtUser($row['txtUser']);
		$usuario->setTxtPassword($row['txtPassword']);
		$usuario->setEstado($row['estado']);
		return $usuario;
	}
}
?>