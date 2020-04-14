<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);

$clienteBLL = new ClienteBLL();
$arregloClientes = $clienteBLL->selectAll();

?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><span class="es">Clientes <small>registrados en la plataforma</small></span><span class="pt hidden">Clientes <small>registrado na plataforma</small></span></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Lista de Clientes</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaClientes">
						<thead>
							<tr>
								<th>ID</th>
								<th>Cliente</th>
								<th><span class="es">Tel&eacute;fono</span><span class="pt hidden">Telefone</span></th>
								<th>Email</th>
								<th><span class="es">Usuario</span><span class="pt hidden">Usu&aacute;rio</span></th>
								<th><span class="es">Acciones</span><span class="pt hidden">Ações</span></th>
							</tr>
						</thead>
						<tbody><?php  
							foreach ($arregloClientes as $objCliente) {?>
							<tr>
								<td><?php echo $objCliente->getIdCliente(); ?></td>
								<td><?php echo utf8_decode($objCliente->getTxtNombre().' '.$objCliente->getTxtApellidos()); ?></td>
								<td><?php echo $objCliente->getTxtTelefono(); ?></td>
								<td><?php echo $objCliente->getTxtEmail(); ?></td>
								<td><?php echo $objCliente->getTxtUser(); ?></td>
								<td>
									<a href="cliente.php?task=cargar&id=<?php echo $objCliente->getIdCliente(); ?>" title="Editar" style="font-size: 18px; line-height: 1;"><i class="fa fa-edit fa-fw"></i></a>
									<a href="javascript:eliminarCliente(<?php echo $objCliente->getIdCliente(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a>
									<a href="lista-propiedades.php?task=cliente&id=<?php echo $objCliente->getIdCliente(); ?>" title="Mostrar Propiedades" style="font-size: 18px; line-height: 1;"><i class="fa fa-map-marker fa-fw"></i></a>
									<a href="enlaceCliente.php?cliente=<?php echo $objCliente->getIdCliente(); ?>" target="_blank" title="Ir al panel de cliente" style="font-size: 18px; line-height: 1;"><i class="fa fa-star fa-fw"></i></a></td>  
								</tr><?php  
							}?>
						</tbody>
					</table>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
					<a href="cliente.php" class="btn btn-primary pull-right"><span class="es">Nuevo Cliente</span><span class="pt hidden">Novo Cliente</span></a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#listaClientes").DataTable({
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

	function eliminarCliente(id){
		alertify.confirm('Eliminar Cliente', 'Esta seguro de eliminar este cliente? Se borraran los datos permanentemente.' 
			,function(){
				$.ajax({
					data : {task:"eliminar", id: id},
					url: 'cliente.php',
					type: 'post',
					success: function (data) {
						alertify.notify('Se elimin&oacute; el cliente correctamente.', 'success', 3, function () {
							window.location.href = 'lista-clientes.php';
						});
					}
				});			
			}
			, function(){});
	}
</script>





