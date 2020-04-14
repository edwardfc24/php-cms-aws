<?php  
date_default_timezone_set('America/La_Paz');
ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);
session_start();
ini_set('memory_limit', '-1');
ini_set('default_charset', 'utf-8');
define('R_PATH', '../');

require_once R_PATH."server/DAL/Connection.php";
include_once R_PATH."server/DTO/PaqueteServicio.php";

include_once R_PATH.'server/BLL/PaqueteServicioBLL.php';

if (isset($_REQUEST['task'])) {
	$task = $_REQUEST['task'];
}

if (isset($_REQUEST['paquete'])) {
	$paquete = $_REQUEST['paquete'];
}

if (isset($_REQUEST['servicios'])) {
	$servicios = $_REQUEST['servicios'];
}

switch ($task) {
	case "grabar":
	grabar($paquete, $servicios);
	break;
	case "consulta":
	consultar($paquete);
	break;
}

function grabar($paquete, $servicios){
	$paqueteServicioBLL = new PaqueteServicioBLL();
	$paqueteServicioBLL->delete($paquete);
	foreach (json_decode($servicios) as $servicio) {
		$relacion = $paqueteServicioBLL->insert($paquete, $servicio);
	}
	echo "Success";
}

function consultar($id){
	$paqueteServicioBLL = new PaqueteServicioBLL();
	$arregloServicios = $paqueteServicioBLL->selectByPaqueteId($id);
	$seleccionados = array();
	foreach ($arregloServicios as $objeto) {
		$seleccionados[] = $objeto->getIdServicio();
	}
	echo json_encode($seleccionados);
}
?>

