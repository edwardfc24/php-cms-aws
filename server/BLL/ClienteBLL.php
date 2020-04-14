<?php 

class ClienteBLL {

	function login($user, $pass) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Clientes_Login(:user, :pass)";
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
		$csql = "CALL sp_Clientes_SelectAll()";
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
		$csql = "CALL sp_Clientes_SelectById(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$objResultado = $this->rowToDto($row);
		return $objResultado;
	}

	function selectByUser($user) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Clientes_SelectByUser(:user)";
		$res = $claseConexion->queryWithParams($csql, array(
			':user' => $user
			));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function insert($name, $lastname, $phone, $email, $user, $pass) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Clientes_Insert(@idCliente, :name, :lastname, :phone, :email, :user,:pass)";
		$res = $claseConexion->queryWithParams($csql, array(
			':name' => $name,
			':lastname' => $lastname,
			':phone' => $phone,
			':email' => $email,
			':user' => $user,
			':pass' => $pass
			));
		$outputArray = $claseConexion->query("select @idCliente")->fetch(PDO::FETCH_ASSOC);
		return $outputArray['@idCliente'];
	}

	function update($id, $name, $lastname, $phone, $email, $user, $pass) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Clientes_Update(:id, :name, :lastname, :phone, :email, :user, :pass)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id,
			':name' => $name,
			':lastname' => $lastname,
			':phone' => $phone,
			':email' => $email,
			':user' => $user,
			':pass' => $pass
			));
	}

	function delete($id) {
		$claseConexion = new Connection();
		$csql = "CALL sp_Clientes_Delete(:id)";
		$res = $claseConexion->queryWithParams($csql, array(
			':id' => $id
			));
	}

	function rowToDto($row) {
		$cliente = new Cliente();
		$cliente->setIdCliente($row['idCliente']);
		$cliente->setTxtNombre($row['txtNombre']);
		$cliente->setTxtApellidos($row['txtApellidos']);
		$cliente->setTxtTelefono($row['txtTelefono']);
		$cliente->setTxtEmail($row['txtEmail']);
		$cliente->setTxtUser($row['txtUser']);
		$cliente->setTxtPassword($row['txtPassword']);
		return $cliente;
	}
}
?>