<?php
session_start();

if($_SESSION[user]==0)
{
    echo "<script>window.location='index.php';</script>";
}
?>

<?php

if($_GET['add']=="ok")
{
    
    if(($_POST['nombre']!=""))
    {
                   
        $sql=mysqli_query($con,"insert into clientes (nombre, direccion, telefono, correo, cuit, codigo) values(lower('$_POST[nombre]'), lower('$_POST[direccion]'), lower('$_POST[telefono]'),'$_POST[correo]', '$_POST[cuit]', '$_POST[codigo]')");
        
        if(!mysqli_error())
        {
            echo "<script>alert('Registro Insertado Correctamente.');</script>";
            echo "<script>window.location='home.php?pagina=clientes';</script>";
        }
            else
            {
                echo "<script>alert('Error: No se pudo insertar el registro.');</script>";
            }
    }
        else
        {
            echo "<script>alert('Complete los Campos Obligatorios (*).');</script>";
        }
}

if($_GET['mod']=="ok")
{

    if(($_POST['nombre']!=""))
    {
                  
            $sql=mysqli_query($con,"update clientes set nombre=lower('$_POST[nombre]'), direccion=lower('$_POST[direccion]'), telefono='$_POST[telefono]', correo='$_POST[correo]', cuit='$_POST[cuit]', codigo='$_POST[codigo]' where id=$_POST[id]");

            if(!mysqli_error())
            {
               
                echo "<script>alert('Registro Modificado Correctamente.');</script>";
                echo "<script>window.location='home.php?pagina=clientes';</script>";
            }
                else
                {
                    echo "<script>alert('Error: No se pudo Modificar el registro.');</script>";
                }
        
    }
        else
        {
            echo "<script>alert('Complete los Campos Obligatorios (*).');</script>";
        }
}

if($_GET[desactivar]!="")
{

        $sql=mysqli_query($con,"update clientes set activo='no' where id=$_GET[desactivar]");
        
        if(!mysqli_error())
        {
            echo "<script>alert('Registro desactivado correctamente.');</script>";
            echo "<script>window.location='home.php?pagina=clientes';</script>";
        }
            else
            {
                echo "<script>alert('Error: No se pudo Eliminar el registro.');</script>";
            }

}

?>

  <div class="tab-content" id="nav-tabContent">
                           
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
               <div id="accordion">
                     <!-- Page Heading -->
                        <div class="card shadow mb-4" id="headingOne">
                            <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary" data-toggle="collapse" data-target="#collapseNuevo" aria-expanded="false" aria-controls="collapseNuevo">Nuevo Cliente</h6>
                            </div>
               
               <?php
                        $showform="";
                        $showtable="";
                        if($_GET[ver]!=0)
                        {
                            $sql=mysqli_query($con,"select *from clientes where id=$_GET[ver]");
                                if(mysqli_num_rows($sql)!=0)
                                {   
                                    $r=mysqli_fetch_array($sql);
                                }
                                $url="home.php?pagina=clientes&mod=ok";
                                $showform="show";
                        }
                            else
                            {
                                $url="home.php?pagina=clientes&add=ok";
                                $showtable="show";
                            }
                    ?>
                        <div id="collapseNuevo" class="collapse <?php echo $showform; ?> m-1" aria-labelledby="headingOne" data-parent="#accordion">    
                            <div class="card-body" >
               
                                <form action="<?php echo $url; ?>" method="POST">
                                <!--Fila 1-->
                                <div class="form-group">
                                    <label for="nombre">Código</label>
                                    <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $r['codigo']; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $r['nombre']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="nombre">CUIT</label>
                                    <input type="text" class="form-control" id="cuit" name="cuit" value="<?php echo $r['cuit']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $r['direccion']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $r['telefono']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="telefono">Email</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $r['correo']; ?>">
                                </div>
                               
                               
                               
                                
                                <input type="hidden" name="id" id="id" value="<?php echo $r['id']; ?>">    
                                <button type="submit" class="btn btn-primary" style="float:right;">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
            

           
            
                     <!-- Page Heading -->
                    <div class="card shadow mb-4 mx-auto" >
                        <div class="card-header py-3" id="headingTwo">
                        <h6 class="m-0 font-weight-bold text-primary" data-toggle="collapse" data-target="#collapseListado" aria-expanded="true" aria-controls="collapseListado">Clientes</h6>
                        </div>
                        <div id="collapseListado" class="collapse <?php echo $showtable; ?>" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body" >
                             <div class="table-responsive" style="padding-right: 1% !important;">
                                    <table class="table table-striped table-bordered display nowrap" id="dataTable-mensajes" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>CUIT</th>
                                        <th>Email</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>CUIT</th>
                                        <th>Email</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php $q=mysqli_query($con,"select * from clientes"); 
                                            if(mysqli_num_rows($q)!=0){
                                                while($r=mysqli_fetch_array($q)){?>
                                                 <tr>
                                                    <td><?php echo $r['codigo']; ?></td>
                                                    <td><?php echo $r['nombre']; ?></td>
                                                    <td><?php echo $r['direccion']; ?></td>
                                                    <td><?php echo $r['telefono']; ?></td>
                                                    <td><?php echo $r['cuit']; ?></td>
                                                    <td><?php echo $r['correo']; ?></td>
                                                    <td>
                                                        <a href="home.php?pagina=clientes&ver=<?php echo $r['id'] ?>" title="Editar" alt="Editar"><i class="fas fa-edit icono_editar"></i></a> 
                                                        <?php 
                                                        $qpc=mysqli_query($con,"select * from presupuestos where id_cliente=".$r['id']);
                                                            if(mysqli_num_rows($qpc)!=0){?>
                                                                <a href="#" onclick="ver_presu(<?php echo $r['id']; ?>)" title="Ver Presupuestos" alt="Ver Presupuestos">
                                                                    <i class="fas fa-file-pdf icono_editar"></i> 
                                                                </a> 
                                                            <?php }?>
                                                        <a href="home.php?pagina=ficha_cliente&id=<?php echo $r['id'];?>" title="Ficha Cliente" alt="Ficha Cliente">
                                                            <i class="fas fa-address-card"></i>
                                                        </a>    
                                                    </td>
                                                 </tr>       
                                             <?php }
                                             }?>       
                                        
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>  
    <div id="VentanaModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Presupuestos emitidos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="table-body" class="table">
              <thead>
                <tr>
                  <th scope="col">Cod.</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">F. Pago</th>
                  <th scope="col">Vencimiento</th>
                  <th scope="col">Total</th>
                  <th scope="col">Opciones</th>
                </tr>
              </thead>
              <tbody >
              </tbody> 
        </table> 
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script src="vendor/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
 //inicio editor
    CKEDITOR.replace('descripcion',
      {
        height  : '500px',
        width   : '100%',

        toolbar : [
        { name: 'document', items : [ 'Undo','Redo','-','NewPage','DocProps','Preview','Print'] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-' ] },
        { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },'/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
        '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
        '/',
        { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
        { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','Source'] },

        ],
        filebrowserUploadUrl: "upload.php",
        allowedContent: true
      });
    //fin editor
</script>

<script>
function ver_presu(id_cliente){

  $.get("modulos/ver_presu.php?id_cliente="+id_cliente,function(dato){
   
      $("#table-body tbody").html(dato);

     $('#VentanaModal').modal({
          backdrop:'static',
          show:true
         }); 
  });  

}


$(document).ready( function () {
    //inicio datatable
    $('#dataTable-mensajes').DataTable({
        sort: true, 
        order : [[0,"desc"]],
        responsive: true,
        language: {
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sProcessing":     "Procesando...",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
        "sInfo":           "Mostrando del _START_ al _END_ - total: _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando del 0 al 0 - total: de 0 registros",
        "sInfoFiltered":   "(filtrado de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
        }
      }
    });

    //inicializar datatable
    $('#dataTable-mensajes').DataTable();
} );    


</script>
