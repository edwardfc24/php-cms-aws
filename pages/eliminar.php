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

if (isset($_REQUEST['ruta'])) {

	$ruta = $_REQUEST['ruta'];
}

try {

	$path = $ruta;

	$s3->deleteObject([
		'Bucket' => $config['s3']['bucket'], 
		'Key' => $path
	]);

	echo "true";
} catch (S3Exception $e) {
	die("false");
}
