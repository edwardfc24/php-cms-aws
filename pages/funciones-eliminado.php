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
include_once R_PATH."server/DTO/Propiedad.php";
include_once R_PATH."server/DTO/Campana.php";

include_once R_PATH.'server/BLL/PropiedadBLL.php';
include_once R_PATH.'server/BLL/CampanaBLL.php';

if (isset($_REQUEST['task'])) {
	$task = $_REQUEST['task'];
}

if (isset($_REQUEST['propiedad'])) {
	$propiedad = $_REQUEST['propiedad'];
}

switch ($task) {
	case "eliminarPropiedad" :
	eliminarPropiedad($propiedad);
	break;
}

function eliminarPropiedad($id) {
	$propiedadBLL = new PropiedadBLL();
	$propiedad = $propiedadBLL->selectById($id);
	$campanaBLL = new CampanaBLL();
	$campanasPropiedad = $campanaBLL->selectByPropertyId($id);
	$porEliminar = array();
	if (isset($id)) {
		require_once "../server/start.php";
		$prefix = $propiedad->getIdPropiedad().'/';
		$objects = $s3->getIterator('ListObjects', array('Bucket' => $config['s3']['bucket'],'Prefix' => $prefix));
		if($objects){
			if(count($campanasPropiedad) > 0){
				foreach ($objects as $object) {
					echo "string";
					$porEliminar[] = ['Key' => $object['Key']];
				}
				$s3->deleteObjects([
					'Bucket' => $config['s3']['bucket'], 
					'Delete' => [ 
					'Objects' => $porEliminar
					]
					]);
			}
		}
		$propiedadBLL->delete($id);
		echo "success";
	}
}

?>

