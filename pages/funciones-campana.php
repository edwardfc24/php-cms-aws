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
include_once R_PATH."server/DTO/Campana.php";
include_once R_PATH."server/DTO/PaqueteServicio.php";
include_once R_PATH."server/DTO/Servicio.php";

include_once R_PATH.'server/BLL/CampanaBLL.php';
include_once R_PATH.'server/BLL/PaqueteServicioBLL.php';
include_once R_PATH.'server/BLL/ServicioBLL.php';

if (isset($_REQUEST['task'])) {
	$task = $_REQUEST['task'];
}

if (isset($_REQUEST['campana'])) {
	$campana = $_REQUEST['campana'];
}

if (isset($_REQUEST['tipo'])) {
	$tipo = $_REQUEST['tipo'];
}

if (isset($_REQUEST['gestion'])) {
	$gestion = $_REQUEST['gestion'];
}

if (isset($_REQUEST['fecha'])) {
	$fecha = $_REQUEST['fecha'];
}

if (isset($_REQUEST['propiedad'])) {
	$propiedad = $_REQUEST['propiedad'];
}

if (isset($_REQUEST['paquete'])) {
	$paquete = $_REQUEST['paquete'];
}

switch ($task) {
	case "insertar":
	insertar($tipo, $gestion, $fecha, $propiedad, $paquete);
	break;
	case "eliminar" :
	eliminar($campana);
	break;
}

function insertar($tipo, $gestion, $fecha, $propiedad, $paquete){
	$campanaBLL = new CampanaBLL();
	$arreglo = explode("/", $fecha);
	$fechaCorregida = $arreglo[2]."-".$arreglo[1]."-".$arreglo[0];
	$campana = $campanaBLL->insert($tipo, $gestion, $fechaCorregida, $propiedad, $paquete);
	$servicioBLL = new ServicioBLL();
	if (isset($campana)) {
		$nombre = $gestion;
		if($tipo == "Safra")
			$nombre = $gestion.'-'.($gestion+1);
		$prefix = $propiedad.'/'.$tipo.'/'.$nombre.'/'.$campana.'/';
		require_once "../server/start.php";
		//Obtengo los servicios seleccionados
		$paqueteServicioBLL = new PaqueteServicioBLL();
		$arregloPaquetes = $paqueteServicioBLL->selectByPaqueteId($paquete);
		foreach ($arregloPaquetes as $relacion) {
			$servicio = $servicioBLL->selectById($relacion->getIdServicio());
			$result = $s3->putObject([
				'Bucket' => $config['s3']['bucket'],
				'Key'    => $prefix.$servicio->getTxtNombrePt().'/',
				'Body'   => ''
			]);
		}
		echo "Success";
	}else {
		echo "Error";
	}
}

function eliminar($id) {
	$campanaBLL = new CampanaBLL();
	$campana = $campanaBLL->selectById($id);
	$porEliminar = array();
	if (isset($campana)) {
		require_once "../server/start.php";
		$prefix = $campana->getIdPropiedad().'/'.$campana->getTipoCampana().'/';
		$objects = $s3->getIterator('ListObjects', array('Bucket' => $config['s3']['bucket'],'Prefix' => $prefix));
		if($objects){
			foreach ($objects as $object) {
				$porEliminar[] = ['Key' => $object['Key']];
			}
			$s3->deleteObjects([
				'Bucket' => $config['s3']['bucket'], 
				'Delete' => [ 
					'Objects' => $porEliminar
				]
			]);
		}
		$campanaBLL->delete($id);
		echo "success";
	}
}

?>

