<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);

$usuarioBLL = new UsuarioBLL();
$arregloUsuarios = $usuarioBLL->selectAll();

?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><span class="es">Usuarios <small>registrados en la plataforma</small></span><span class="pt hidden">Usu&aacute;rios <small>registrado na plataforma</small></span></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Lista de <span class="es">Usuarios</span><span class="pt hidden">Usu&aacute;rios</span></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaUsuarios">
						<thead>
							<tr>
								<th>ID</th>
								<th><span class="es">Nombre</span><span class="pt hidden">Nome</span></th>
								<th>Email</th>
								<th><span class="es">Usuario</span><span class="pt hidden">Usu&aacute;rio</span></th>
								<th><span class="es">Acciones</span><span class="pt hidden">Ações</span></th>
							</tr>
						</thead>
						<tbody><?php  
							foreach ($arregloUsuarios as $objUsuario) {?>
							<tr>
								<td><?php echo $objUsuario->getIdUsuario(); ?></td>
								<td><?php echo utf8_decode($objUsuario->getTxtNombre().' '.$objUsuario->getTxtApellidos()); ?></td>
								<td><?php echo $objUsuario->getTxtEmail(); ?></td>
								<td><?php echo $objUsuario->getTxtUser(); ?></td>
								<td>
									<a href="usuario.php?task=cargar&id=<?php echo $objUsuario->getIdUsuario(); ?>" title="Editar" style="font-size: 18px; line-height: 1;"><i class="fa fa-edit fa-fw"></i></a>
									<a href="javascript:eliminarUsuario(<?php echo $objUsuario->getIdUsuario(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a></td>
								</tr><?php  
							}?>
						</tbody>
					</table>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
					<a href="usuario.php" class="btn btn-primary pull-right"><span class="es">Nuevo Usuario</span><span class="pt hidden">Novo Usu&aacute;rio</span></a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#listaUsuarios").DataTable({
			responsive: true,
			"ordering": false,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
			language: {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ usuarios",
				"sInfoEmpty": "Mostrando usuarios del 0 al 0 de un total de 0 usuarios",
				"sInfoFiltered": "(filtrado de un total de _MAX_ usuarios)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			buttons: [
			'excel'
			]
		});
	});

	function eliminarUsuario(id){
		alertify.confirm('Eliminar Usuario', 'Esta seguro de eliminar este usuario? Se borraran los datos permanentemente.' 
			,function(){
				$.ajax({
					data : {task:"eliminar", id: id},
					url: 'usuario.php',
					type: 'post',
					success: function (data) {
						alertify.notify('Se elimin&oacute; el usuario correctamente.', 'success', 3, function () {
							window.location.href = 'lista-usuarios.php';
						});
					}
				});			
			}
			, function(){});
	}
</script>





