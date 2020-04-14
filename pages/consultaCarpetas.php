<?php 
define('R_PATH', '../');
ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);
date_default_timezone_set('America/La_Paz');
use Aws\S3\Exception\S3Exception;
require_once R_PATH."server/start.php";
require_once R_PATH."server/DAL/Connection.php";
include_once R_PATH."server/DTO/Propiedad.php";
include_once R_PATH."server/DTO/Campana.php";
include_once R_PATH."server/DTO/Servicio.php";

include_once R_PATH.'server/BLL/PropiedadBLL.php';
include_once R_PATH.'server/BLL/CampanaBLL.php';
include_once R_PATH.'server/BLL/ServicioBLL.php';


if (isset($_REQUEST['id'])) 
	$id = $_REQUEST['id'];

if (isset($_REQUEST['servicio'])) 
	$idServicio = $_REQUEST['servicio'];

$files = array();

try {
	$campanaBLL = new CampanaBLL();
	$campana = $campanaBLL->selectById($id);
	$nombre = $campana->getGestion();
	if($campana->getTipoCampana() == "Safra")
		$nombre = $nombre.'-'.($nombre+1);
	$propiedadBLL = new PropiedadBLL();
	$propiedad = $propiedadBLL->selectById($campana->getIdPropiedad());
	$servicioBLL = new ServicioBLL();
	$servicio =  $servicioBLL->selectById($idServicio);
	$prefix = $propiedad->getIdPropiedad().'/'.$campana->getTipoCampana().'/'.$nombre.'/'.$campana->getIdCampana().'/'.$servicio->getTxtNombrePt().'/';
	$objects = $s3->getIterator('ListObjects', array('Bucket' => $config['s3']['bucket'],'Prefix' => $prefix));
	foreach ($objects as $object) {
		if(substr($object['Key'], -1) == '/')
			$files[] = str_replace_count($propiedad->getIdPropiedad().'/'.$campana->getTipoCampana().'/'.$nombre.'/'.$campana->getIdCampana().'/','',$object['Key'],1);
		else 
			$files[] = str_replace_count($propiedad->getIdPropiedad().'/'.$campana->getTipoCampana().'/'.$nombre.'/'.$campana->getIdCampana().'/','',$object['Key'],1).'*'.$object['Size'];
	}
	$finalTree = parseArrayToTree($files);
	header('Content-type: application/json');
	echo json_encode($finalTree);
} catch (S3Exception $e) {
	$mensaje =[
		"estado" => "error",
		"mensaje" => "Error al crear las carpetas.",
	];
	die(json_encode($mensaje));
}


function parseArrayToTree($paths) {
	sort($paths);
	$array = array();
	foreach ($paths as $path)
	{
		$path = trim($path, '/');
		$list = explode('/', $path);
		$n = count($list);

		$arrayRef = &$array; 
		for ($i = 0; $i < $n; $i++)
		{
			$key = $list[$i];
			$arrayRef = &$arrayRef[$key];
		}
	}
	$dataArray = buildUL($array, '');
	return $dataArray;
}

function buildUL($array, $prefix) {
	$finalArray = array();

	foreach ($array as $key => $value)
	{
		$levelArray = array();
		$path_parts = pathinfo($key);
		if (!empty($path_parts['extension']) && $path_parts['extension'] != '')
		{
			$extension = $path_parts['extension'];
		}
		else
		{
			if (empty($value))
			{
				$extension = "";
			}
			else if (is_array($value))
			{
				$extension = 'folder';
			}
		}

		if (is_array($value))
      { //its a folder
      	$levelArray['name'] = $key.'';
      	$levelArray['type'] = 'folder';
      	$levelArray['path'] = $prefix . $key;
      }
      else
      { //its a file
      	if(strpos($key, '.') !== false){
      		$campos = explode('*', $key);
      		$levelArray['name'] = $campos[0];
      		$levelArray['type'] = 'file';
      		$levelArray['path'] = $prefix . $campos[0];
      		$levelArray['size'] = $campos[1];
      	} else {
      		$levelArray['name'] = $key;
      		$levelArray['type'] = 'folder';
      		$levelArray['path'] = $prefix . $key;
      	}
      }

      // if the value is another array, recursively build the list$key
      if (is_array($value))
      {
      	$levelArray['items'] = buildUL($value, $prefix . $key . "/");
      }
      else{
      	$levelArray['items'] = array();
      }

      $finalArray[] = $levelArray;
    } //end foreach

    return $finalArray;
}

function str_replace_count($search,$replace,$subject,$times) {
	$subject_original=$subject;
	$len=strlen($search);    
	$pos=0;
	for ($i=1;$i<=$times;$i++) {
		$pos=strpos($subject,$search,$pos);
		if($pos!==false) {                
			$subject=substr($subject_original,0,$pos);
			$subject.=$replace;
			$subject.=substr($subject_original,$pos+$len);
			$subject_original=$subject;
		} else {
			break;
		}
	}
	return($subject);
}


?>