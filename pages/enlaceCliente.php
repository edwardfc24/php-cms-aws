<?php

if (isset($_REQUEST['cliente'])) {

	$id = $_REQUEST['cliente'];
}

header("Location: http://clientes.pixadvisor.com.br/pages/login.php?id=$id");

?>