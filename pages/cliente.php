<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';


$documentReadyScript = "";
$task = "";
$mostrar = true;
$nombre = "";
$apellidos = "";
$telefono = ""; 	
$email = "";
$user = "";
// Consultando el request 
if (isset($_REQUEST['task']))
	$task = $_REQUEST['task'];

$tituloPagina = "Registro de Cliente";


if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	$clienteBLL = new ClienteBLL();
	$cliente = $clienteBLL->selectById($id);
	$nombre = $cliente->getTxtNombre();
	$apellidos = $cliente->getTxtApellidos();
	$telefono = $cliente->getTxtTelefono();
	$email = $cliente->getTxtEmail();
	$user = $cliente->getTxtUser();
	$mostrar = false;
}

if (isset($_REQUEST['nombre'])) {
	$nombre = $_REQUEST['nombre'];
}

if (isset($_REQUEST['apellidos'])) {
	$apellidos = $_REQUEST['apellidos'];
}

if (isset($_REQUEST['telefono'])) {
	$telefono = $_REQUEST['telefono'];
}

if (isset($_REQUEST['email'])) {
	$email = $_REQUEST['email'];
}

if (isset($_REQUEST['user'])) {
	$user = $_REQUEST['user'];
}

if (isset($_REQUEST['pass'])) {
	$pass = $_REQUEST['pass'];
}

if (isset($_REQUEST['state'])) {
	$state = $_REQUEST['state'];
} else {
	if($task == "")
		$state = 1;
	elseif($task == "actualizar")
		$state = 0;
}
?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-6 col-sm-8 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><?php echo $tituloPagina; ?></h2>
					<div class="clearfix"></div>
				</div>
				<form id="creacionCliente" role="form" action="cliente.php" method="POST">
					<div class="x_content">

						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<input type="hidden" name="task" value="<?php echo ($task == 'cargar')?'actualizar':'insertar'; ?>">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Nombre</span><span class="pt hidden">Nome</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="nombre" type="text" autocomplete="off" value="<?php echo $nombre; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Apellidos</span><span class="pt hidden">Sobrenomes</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="apellidos" type="text" autocomplete="off" value="<?php echo $apellidos; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Tel&eacute;fono</span><span class="pt hidden">Telefone</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="telefono" type="text" autocomplete="off" value="<?php echo $telefono; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="email" type="email" autocomplete="off" value="<?php echo $email; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group  <?php echo ($mostrar)?'':'hidden'; ?>">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Usuario</span><span class="pt hidden">Usu&aacute;rio</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="user" type="text" autocomplete="off" value="<?php echo $user; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Contrase&ntilde;a</span><span class="pt hidden">Senha</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="pass" type="password">
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div>
						<input type="submit" class="btn btn-primary pull-right" value="Guardar">
						<a href="lista-clientes.php" class="btn btn-danger pull-right">Cancelar</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 

switch ($task) {
	case "insertar":
	insertar($nombre, $apellidos, $telefono, $email, $user, $pass);
	break;
	case "eliminar" :
	eliminar($id);
	break;
	case "actualizar":
	echo $state;
	actualizar($id, $nombre, $apellidos, $telefono, $email, $pass);
	break;
}

function insertar($nombre, $apellidos, $telefono, $email, $user, $pass){
	$clienteBLL = new ClienteBLL();
	$clienteBLL = new ClienteBLL();
	$existeUser = $clienteBLL->selectByUser($user);
	if($existeUser == ''){
		$existeUser = $clienteBLL->selectByUser($user);
		if($existeUser == ''){
			$cliente = $clienteBLL->insert($nombre, $apellidos, $telefono, $email, $user, sha1($pass));
			if (isset($cliente)) {
				$mail = new PHPMailer;
				$mail->Host = 'smtp.pixadvisor.com.br';  
				$mail->Username = 'contacto@pixadvisor.com.br';   
				$mail->Password = 'contacto!123';          
				$mail->SMTPSecure = 'tls';                            
				$mail->Port = 587;

				$mail->setFrom('contacto@pixadvisor.com.br', 'PixAdvisor');
				$mail->addAddress($email);    
				$mail->addReplyTo('contacto@pixadvisor.com.br', 'PixAdvisor');
				$mail->isHTML(true);                                 

				$mail->Subject = 'PixAdvisor - Panel Clientes';
				$mail->Body    = '<p><h1>PixAdvisor - Asignaci&oacute;n de  Contrase&ntilde;a</h1></p><p>Saludos '.$nombre.'</p><p>Esta es la contrase&ntilde;a que se te ha asignado para acceder al Administraci&oacute;n de Informaci&oacute;n en este <a href="http://clientes.pixadvisor.com.br/" target="_blank">enlace</a>.</p><p style="text-align:center; font-weight: bold;">Usuario: '.$user.'</p><p style="text-align:center; font-weight: bold;">Contrase&ntilde;a: '.$pass.'</p>';

				if(!$mail->send()) {
					alert_redirect("notice", "No se ha podido enviar el correo, pero el cliente se ha creado.", "lista-clientes.php");
				} else {
					alert_redirect("success", "Cliente insertado correctamente. Correo Enviado.", "lista-clientes.php");
				}
			} else {
				alert_redirect("error", "Error al insertar Cliente, por favor intente nuevamente", "lista-clientes.php");
			}
		}else {
			alert("notice", "El cliente ingresado ya ha sido ocupado, ingrese otro diferente.");
		}
	}else {
		alert("notice", "El cliente ingresado ya ha sido ocupado, ingrese otro diferente.");
	}
}
function actualizar($id, $nombre, $apellidos, $telefono, $email, $pass){
	$clienteBLL = new ClienteBLL();
	$aux = $clienteBLL->selectById($id);
	$cliente = $clienteBLL->update($id, $nombre, $apellidos, $telefono, $email, $aux->getTxtUser(), sha1($pass));
	
	if (isset($id)) {
		$mail = new PHPMailer;
		$mail->Host = 'smtp.pixadvisor.com.br';  
		$mail->Username = 'plataforma@pixadvisor.com.br';   
		$mail->Password = 'plataforma!123';          
		$mail->SMTPSecure = 'tls';                            
		$mail->Port = 587;

		$mail->setFrom('plataforma@pixadvisor.com.br', 'PixAdvisor');
		$mail->addAddress($email);    
		$mail->addReplyTo('plataforma@pixadvisor.com.br', 'PixAdvisor');
		$mail->isHTML(true);                                 

		$mail->Subject = 'PixAdvisor - Panel Clientes';
		$mail->Body    = '<p><h1>PixAdvisor - Asignaci&oacute;n de  Contrase&ntilde;a</h1></p><p>Saludos '.$nombre.'</p><p>Esta es la contrase&ntilde;a que se te ha asignado para acceder al Administraci&oacute;n de Informaci&oacute;n en este <a href="http://www.pixadvisor.com.br/panelCliente/" target="_blank">enlace</a>.</p><p style="text-align:center; font-weight: bold;">Usuario: '.$user.'</p><p style="text-align:center; font-weight: bold;">Contrase&ntilde;a: '.$pass.'</p>';

		if(!$mail->send()) {
			alert_redirect("notice", "No se ha podido enviar el correo, pero el cliente se ha creado.", "lista-clientes.php");
		} else {
			alert_redirect("success", "Cliente insertado correctamente. Correo Enviado.", "lista-clientes.php");
		}
	} else {
		alert_redirect("error", "Error al insertar Cliente, por favor intente nuevamente", "lista-clientes.php");
	}
}
function eliminar($id) {
	$clienteBLL = new ClienteBLL();
	$clienteBLL->delete($id);
	alert_redirect("success", "Cliente eliminado correctamente.", "lista-clientes.php");
}
?>
<?php include_once 'footer.php'; ?>



