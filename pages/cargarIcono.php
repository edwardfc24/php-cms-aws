<?php

$allowed = array('png', 'jpg', 'jpeg');

$relativo = "";

if (isset($_REQUEST['idServicio'])) {
	$id = $_REQUEST['idServicio'];
}

if(isset($_FILES['upImage']) && $_FILES['upImage']['error'] == 0){

	$extension = pathinfo($_FILES['upImage']['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error in extension"}';
		exit;
	}

	move_uploaded_file($_FILES['upImage']['tmp_name'], '../iconos/'.$id.'.'.$extension);
}
echo '{"status":"error general"}';
exit;