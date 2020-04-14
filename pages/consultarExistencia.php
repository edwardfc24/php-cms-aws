<?php
define('R_PATH', '../');
ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);
date_default_timezone_set('America/La_Paz');
use Aws\S3\Exception\S3Exception;
use Aws\CommandPool;
require_once R_PATH."server/start.php";

if (isset($_REQUEST['ruta'])) {
	$ruta = $_REQUEST['ruta'];
}

$mensaje =[
"estado" => "success",
"mensaje" => "Se eliminó la carpeta correctamente."
];

$response = false;
try {
	$bucket = $config['s3']['bucket'];
	$objects = $s3->getIterator('ListObjects', array('Bucket' => $config['s3']['bucket'],'Prefix' => $ruta));
	foreach ($objects as $object) 
		$response = true;
	$mensaje["mensaje"] = $response;
	echo json_encode($mensaje);
} catch (S3Exception $e) {
	$mensaje =[
	"estado" => "error",
	"mensaje" => "Error al eliminar la carpeta.",
	];
	die(json_encode($mensaje));
}
?>