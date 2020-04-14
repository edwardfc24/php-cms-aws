<?php
date_default_timezone_set('America/La_Paz');
session_start();
ini_set('memory_limit', '-1');
ini_set('default_charset', 'utf-8');

unset($_SESSION["userId"]);

define('ROOT_PATH', '../');


if (isset($_POST["username"]) && isset($_POST["password"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  include_once ROOT_PATH."server/DTO/Usuario.php";
  require_once ROOT_PATH."server/DAL/Connection.php";
  include_once ROOT_PATH.'server/BLL/UsuarioBLL.php';

  $UsuarioBLL = new UsuarioBLL();
  $usuarioAutenticado = $UsuarioBLL->login($username, sha1($password));

  if ($usuarioAutenticado != null) {
    $_SESSION["userId"] = $usuarioAutenticado->getIdUsuario();
    header("Location: index.php");
  } else {
    $mostrar = true;
  }
} else {
  $mostrar = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>PixAdvisor</title>

  <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="../build/css/custom.min.css" rel="stylesheet">
  <link href="../build/css/bg.css" rel="stylesheet">
</head>

<body class="login bg-container">
  <div>
    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form role="form" action='login.php' method="POST">
            <input type="hidden" name="bandera" value="True">
            <h1><span class="es">Formulario de Acceso</span><span class="pt hidden">Formul&aacute;rio de acesso</span></h1>
            <?php 
            if ($mostrar) {
              echo '<div class="alert alert-danger"><span class="es">Usuario o contraseña incorrectos, intente nuevamente</span><span class="pt hidden">Nome de usuário ou senha incorretos, tente novamente</span></div>';
            }
            ?>
            <div>
              <input type="text" name="username" class="form-control" placeholder="Usuario" required="" />
            </div>
            <div>
              <input type="password" name="password" class="form-control" placeholder="Contrase&ntilde;a" required="" />
            </div>
            <div>
              <button type="submit" class="btn btn-info submit" href="index.html"><span class="es">Ingresar</span><span class="pt hidden">Iniciar sessão</span></button>
              <p style="text-align: center; margin: 10px;"><small><span class="es">Regresar a</span><span class="pt hidden">Voltar para</span> <a href="http://www.pixadvisor.com.br">PixAdvisor</a></small></p>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              <div class="clearfix"></div>
              <br />
              <div>
                <h1>PixAdvisor</h1>
                <p>©<?php echo date("Y"); ?> <span class="es">Todos los derechos reservados.</span><span class="pt hidden">Todos os direitos reservados.
                </span></p>
              </div>
            </div>
          </form>
        </section>
      </div>
    </div>
    <script type="text/javascript">
      localStorage.setItem("lang", lenguaje);
      localStorage.setItem("actualPropiedad", "");
    </script>
  </div>
</body>
</html>
