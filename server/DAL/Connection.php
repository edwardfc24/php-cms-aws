<?php 

class Connection {

	private $server = "localhost";
	private $usr = "root";
	private $pass = "";
	private $db = "admin_pixadvisor";
	private $connection;

	function getConnection() {
		if ($this->connection == null) {
			$this->connection = new PDO("mysql:host=$this->server;dbname=$this->db", $this->usr, $this->pass);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
		}
		return $this->connection;
	}

	function query($csql) {
		$this->getConnection()->query("SET NAMES 'utf8'");
		return $this->getConnection()->query($csql);
	}

	function queryWithParams($csql, $paramArray) {
		$q = $this->getConnection()->prepare($csql);
		$q->execute($paramArray);
		return $q;
	}

	function getLastInsertedId() {
		return $this->getConnection()->lastInsertId();
	}

}
?>