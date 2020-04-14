<?php

if (isset($_REQUEST['tecnico'])) {

	$id = $_REQUEST['tecnico'];
}

header("Location: http://www.sueloyagua.com/controlProcesos/login.php?id=$id");

?>