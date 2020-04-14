<?php
define('R_PATH', '../');
use Aws\S3\Exception\S3Exception;
use Aws\CommandPool;
require_once R_PATH."server/start.php";
require_once R_PATH."server/DAL/Connection.php";
include_once R_PATH."server/DTO/Propiedad.php";

include_once R_PATH.'server/BLL/PropiedadBLL.php';
// A list of permitted file extensions
$allowed = array('kmz', 'kml', 'pdf', 'zip', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'geojson');

$relativo = "";

if (isset($_REQUEST['path'])) {

	$ruta = $_REQUEST['path'];
}
if (isset($_REQUEST['relative'])) {
	$relativo = $_REQUEST['relative'];
}

if(isset($_FILES['upImage']) && $_FILES['upImage']['error'] == 0){

	$extension = pathinfo($_FILES['upImage']['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error in extension"}';
		exit;
	}

	if(move_uploaded_file($_FILES['upImage']['tmp_name'], '../temp/'.$_FILES['upImage']['name'])){
		try {
			if($relativo != '')
				$path = $ruta.$relativo.$_FILES['upImage']['name'];
			else
				$path = $ruta.$_FILES['upImage']['name'];

			$result = $s3->putObject([
				'Bucket' => $config['s3']['bucket'],
				'ACL' => 'public-read',
				'Key'    => $path,
				'Body'   => fopen('../temp/'.$_FILES['upImage']['name'], 'rb')
			]);

			unlink('../temp/'.$_FILES['upImage']['name']);
			echo '{"status":"success"}';
			exit;

		} catch (S3Exception $e) {
			$mensaje =[
				"estado" => "error",
				"mensaje" => "Error al subir archivos.",
			];
			die(json_encode($mensaje));
		}
	}
}
echo '{"status":"error general"}';
exit;