<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';


$documentReadyScript = "";
$task = "";
$mostrar = true;
$nombre = "";
$apellidos = "";
$email = "";
$user = "";
// Consultando el request 
if (isset($_REQUEST['task']))
	$task = $_REQUEST['task'];

$tituloPagina = "Registro de Usuario";

if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	$usuarioBLL = new UsuarioBLL();
	$usuario = $usuarioBLL->selectById($id);
	$nombre = $usuario->getTxtNombre();
	$apellidos = $usuario->getTxtApellidos();
	$email = $usuario->getTxtEmail();
	$user = $usuario->getTxtUser();
	$mostrar = false;
	$state = $usuario->getEstado();
}

if (isset($_REQUEST['nombre'])) {
	$nombre = $_REQUEST['nombre'];
}
if (isset($_REQUEST['apellidos'])) {
	$apellidos = $_REQUEST['apellidos'];
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
					<h2><span class="es">Registro de Usuario</span><span class="pt hidden">Registre Usu&aacute;rio</span></h2>
					<div class="clearfix"></div>
				</div>
				<form id="creacionUsuario" role="form" action="usuario.php" method="POST">
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
						<div class="checkbox">
							<label class="">
								<div class="icheckbox_flat-green <?php echo ($state == 1)?'checked':''; ?>">
									<input class="flat" <?php echo ($state == 1)?'checked':''; ?>  name="state" type="checkbox" value="1">
									<ins class="iCheck-helper"></ins>
								</div> <span class="es">Activo</span><span class="pt hidden">Ativo</span>
							</label>
						</div>
					</div>
					<div>
						<input type="submit" class="btn btn-primary pull-right" value="Guardar">
						<a href="lista-usuarios.php" class="btn btn-danger pull-right">Cancelar</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 

switch ($task) {
	case "insertar":
	insertar($nombre, $apellidos, $email, $user, $pass, $state);
	break;
	case "eliminar" :
	eliminar($id);
	break;
	case "actualizar":
	echo $state;
	actualizar($id, $nombre, $apellidos, $email, $pass, $state);
	break;
}

function insertar($nombre, $apellidos, $email, $user, $pass, $state){
	$usuarioBLL = new UsuarioBLL();
	$clienteBLL = new ClienteBLL();
	$existeUser = $clienteBLL->selectByUser($user);
	if($existeUser == ''){
		$existeUser = $usuarioBLL->selectByUser($user);
		if($existeUser == ''){
			$usuario = $usuarioBLL->insert($nombre, $apellidos, $email, $user, sha1($pass));
			if (isset($usuario)) {
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

				$mail->Subject = 'PixAdvisor - Administraci&oacute;n';
				$mail->Body    = '<p><h1>PixAdvisor - Env&iacute;o de  Contrase&ntilde;a</h1></p><p>Saludos '.$nombre.'</p><p>Esta es la contrase&ntilde;a que se te ha asignado para acceder al Administrador de PixAdvisor.</p><p style="text-align:center; font-weight: bold;">Usuario: '.$usuario.'</p><p style="text-align:center; font-weight: bold;">Contrase&ntilde;a: '.$pass.'</p>';

				if(!$mail->send()) {
					alert_redirect("notice", "No se ha podido enviar el correo, pero el usuario se ha creado.", "lista-usuarios.php");
				} else {
					alert_redirect("success", "Usuario insertado correctamente. Correo Enviado.", "lista-usuarios.php");
				}
			} else {
				alert_redirect("error", "Error al insertar Usuario, por favor intente nuevamente", "lista-usuarios.php");
			}
		}else {
			alert("notice", "El usuario ingresado ya ha sido ocupado, ingrese otro diferente.");
		}
	}else {
		alert("notice", "El usuario ingresado ya ha sido ocupado, ingrese otro diferente.");
	}
}
function actualizar($id, $nombre, $apellidos, $email, $pass, $state){
	$usuarioBLL = new UsuarioBLL();
	$aux = $usuarioBLL->selectById($id);
	$usuario = $usuarioBLL->update($id, $nombre, $apellidos, $email, $aux->getTxtUser(), sha1($pass), $state);
	
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

		$mail->Subject = 'PixAdvisor - Administraci&oacute;n';
		$mail->Body    = '<p><h1>PixAdvisor - Actualizaci&oacute;n de  Contrase&ntilde;a</h1></p><p>Saludos '.$nombre.'</p><p>Actualizaci&oacute;n de datos. Esta es la contrase&ntilde;a que haz ingresado para acceder al Administrador de PixAdvisor.</p><p style="text-align:center; font-weight: bold;">Usuario: '.$aux->getTxtUser().'</p><p style="text-align:center; font-weight: bold;">Contrase&ntilde;a: '.$pass.'</p>';

		if(!$mail->send()) {
			alert_redirect("notice", "No se ha podido enviar el correo, pero el usuario se ha creado.", "lista-usuarios.php");
		} else {
			alert_redirect("success", "Usuario insertado correctamente. Correo Enviado.", "lista-usuarios.php");
		}
	} else {
		alert_redirect("error", "Error al insertar Usuario, por favor intente nuevamente", "lista-usuarios.php");
	}
}
function eliminar($id) {
	$usuarioBLL = new UsuarioBLL();
	$usuarioBLL->delete($id);
	alert_redirect("success", "Usuario eliminado correctamente.", "lista-usuarios.php");
}
?>
<?php include_once 'footer.php'; ?>



