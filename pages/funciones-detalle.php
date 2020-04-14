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
include_once R_PATH."server/DTO/Servicio.php";
include_once R_PATH."server/DTO/DetalleServicio.php";

include_once R_PATH.'server/BLL/ServicioBLL.php';
include_once R_PATH.'server/BLL/DetalleServicioBLL.php';

if (isset($_REQUEST['task'])) {
	$task = $_REQUEST['task'];
}

if (isset($_REQUEST['detalle'])) {
	$detalle = $_REQUEST['detalle'];
}

if (isset($_REQUEST['nombre_es'])) {
	$nombre_es = $_REQUEST['nombre_es'];
}

if (isset($_REQUEST['nombre_pt'])) {
	$nombre_pt = $_REQUEST['nombre_pt'];
}

if (isset($_REQUEST['estado'])) {
	$state = $_REQUEST['estado'];
}

if (isset($_REQUEST['servicio'])) {
	$servicio = $_REQUEST['servicio'];
}

if (isset($_REQUEST['orden'])) {
	$orden = $_REQUEST['orden'];
}


switch ($task) {
	case "insertar":
	insertar($nombre_es, $nombre_pt, $state, $servicio, $orden);
	break;
	case "eliminar" :
	eliminar($detalle);
	break;
	case "actualizar":
	actualizar($detalle, $nombre_es, $nombre_pt, $state, $servicio, $orden);
	break;
}

function insertar($nombre_es, $nombre_pt, $state, $servicio, $orden){
	$detalleServicioBLL = new DetalleServicioBLL();
	$detalleServicio = $detalleServicioBLL->insert($nombre_es, $nombre_pt, $state, $servicio, $orden);
	if (isset($detalleServicio)) {
		echo "Success";
	}else {
		echo "Error";
	}
}

function actualizar($id, $nombre_es, $nombre_pt, $state, $servicio, $orden){
	$detalleServicioBLL = new DetalleServicioBLL();
	$detalleServicio = $detalleServicioBLL->update($id, $nombre_es, $nombre_pt, $state, $servicio, $orden);
	if (isset($id)) {	
		echo "Success";
	}else {
		echo "Error";
	}
}

function eliminar($id) {
	$detalleServicioBLL = new DetalleServicioBLL();
	$detalleServicioBLL->delete($id);
	echo "Success";
}
?>

