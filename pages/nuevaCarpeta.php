<?php 
define('R_PATH', '../');
ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);
date_default_timezone_set('America/La_Paz');
use Aws\S3\Exception\S3Exception;
use Aws\CommandPool;
require_once R_PATH."server/start.php";
require_once R_PATH."server/DAL/Connection.php";

if (isset($_REQUEST['ruta']))
	$ruta = $_REQUEST['ruta'];

if (isset($_REQUEST['nombre']))
	$nombre = $_REQUEST['nombre'];

try {

	$bucket = $config['s3']['bucket'];
	$commands = array();
	$porEliminar = array();
	
	$mensaje =[
		"estado" => "success",
		"mensaje" => "Se creó la carpeta correctamente."
	];

	$prefix = $ruta.$nombre.'/';

	$result = $s3->putObject([
		'Bucket' => $config['s3']['bucket'],
		'Key'    => $prefix,
		'Body'   => ''
	]);

	echo json_encode($mensaje);

} catch (S3Exception $e) {
	$mensaje =[
		"estado" => "error",
		"mensaje" => "Error al crear la carpeta.",
	];
	die(json_encode($mensaje));
}


?>