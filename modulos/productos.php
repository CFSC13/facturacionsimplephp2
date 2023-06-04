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
        $archivo=$_FILES['foto']['name'];
        if($archivo!="")
        {
        $extension = explode(".",$archivo);
                if((end($extension)=="jpg") || (end($extension)=="jpeg") || (end($extension)=="JPG") || (end($extension)=="JPEG") || (end($extension)=="png") || (end($extension)=="PNG"))
                        {
                            if (is_uploaded_file($_FILES['foto']['tmp_name'])) 
                            {
                            $qu=time();
                            copy($_FILES['foto']['tmp_name'], "fotos/".$qu.".".end($extension));
                            $archivo=$qu.".".end($extension);//aca queda guardado el nombre para la bd
                            }
                            else{
                                    echo "<p>Error: No se pudo subir el archivo.</p>";
                                }
                        }
                else{
                        echo "<p>Error: El archivo debe ser una IMG</p>";
                    }
                }
        else{
            echo "<p>Error: Debe seleccionar un archivo.</p>";
            }  

             echo $_POST['nombre'];      
       
         $sql = mysqli_query($con, "INSERT INTO productos (codigo_barra, nombre, descripcion, precio, stock, foto, id_categoria)
         VALUES ('$_POST[codigo_barra]', lower('$_POST[nombre]'), '$_POST[descripcion]', '$_POST[precio]', '$_POST[stock]', '$archivo', $_POST[id_categoria])");

         echo mysqli_error($con);

        if(!mysqli_error($con))
        {
            echo "<script>alert('Registro Insertado Correctamente.');</script>";
            echo "<script>window.location='home.php?pagina=productos';</script>";
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

if ($_GET['mod'] == "ok") {
    if (!empty($_POST['nombre'])) {
        $archivo = "";
        
        // Verificar si se ha subido una foto
        if (!empty($_FILES['foto']['name'])) {
            $archivo = $_FILES['foto']['name'];
            
            // Realizar la comprobación de la extensión del archivo
            $extension = explode(".", $archivo);
            $extensionesPermitidas = array("jpg", "jpeg", "JPG", "JPEG", "png", "PNG");
            
            if (in_array(end($extension), $extensionesPermitidas)) {
                if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
                    $qu = time();
                    copy($_FILES['foto']['tmp_name'], "fotos/" . $qu . "." . end($extension));
                    $archivo = "foto='" . $qu . "." . end($extension) . "'";
                    
                    // Eliminar la foto actual
                    unlink('fotos/' . $_POST['foto_actual']);
                } else {
                    echo "<p>Error: No se pudo subir el archivo.</p>";
                }
            } else {
                echo "<p>Error: El archivo debe ser una imagen.</p>";
            }
        }
        
        // Construir la consulta SQL
        $sql = "UPDATE productos SET codigo_barra='" . $_POST['codigo_barra'] . "', nombre=lower('" . $_POST['nombre'] . "'), descripcion='" . $_POST['descripcion'] . "', precio='" . $_POST['precio'] . "', stock='" . $_POST['stock'] . "', id_categoria='" . $_POST['id_categoria'] . "'";
        
        if (!empty($archivo)) {
            $sql .= ", " . $archivo;
        }
        
        $sql .= " WHERE id_producto='" . $_POST['id_producto'] . "'";
        
        // Ejecutar la consulta SQL
        $resultado = mysqli_query($con, $sql);
        
        if ($resultado) {
            echo "<script>alert('Registro modificado correctamente.');</script>";
            echo "<script>window.location='home.php?pagina=productos';</script>";
        } else {
            echo "<script>alert('Error: No se pudo modificar el registro.');</script>";
            echo mysqli_error($con);
        }
    } else {
        echo "<script>alert('Complete los campos obligatorios (*).');</script>";
    }
}




if($_GET[del]!="")
{
    echo $_GET[del];
    //se elimina foto  
    $sql_foto="select foto from productos where id_producto=".$_GET['del'];
	$r_foto=mysqli_fetch_array(mysqli_query($con, $sql_foto));
	copy('fotos/'.$r_foto['foto'], 'fotos_anuladas/'.$_GET['del'].'_'.$r_foto['foto']);//copiar
	unlink('fotos/'.$r_foto['foto']);//eliminar

	//elimino el registro
	$sql="delete from productos where id_producto=".$_GET['del'];
	$resultado=mysqli_query($con,$sql);
	if(!mysqli_error($con))

        {
            echo "<script>alert('Registro Eliminado Correctamente.');</script>";
            echo "<script>window.location='home.php?pagina=productos';</script>";
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
                            <h6 class="m-0 font-weight-bold text-primary" data-toggle="collapse" data-target="#collapseNuevo" aria-expanded="false" aria-controls="collapseNuevo">Nuevo producto</h6>
                            </div>
               
               <?php
                        $showform="";
                        $showtable="";
                        if($_GET[ver]!=0)
                        {
                            $sql=mysqli_query($con,"select *from productos where id_producto=$_GET[ver]");
                                if(mysqli_num_rows($sql)!=0)
                                {   
                                    $r=mysqli_fetch_array($sql);
                                }
                                $url="home.php?pagina=productos&mod=ok";
                                $showform="show";
                        }
                            else
                            {
                                $url="home.php?pagina=productos&add=ok";
                                $showtable="show";
                            }
                    ?>
                        <div id="collapseNuevo" class="collapse <?php echo $showform; ?> m-1" aria-labelledby="headingOne" data-parent="#accordion">    
                            <div class="card-body" >
               
                            <form action="<?php echo $url; ?>" method="POST" enctype="multipart/form-data">
                                <!--Fila 1-->
                                <div class="form-group">
                                    <label for="nombre">Código de Barras</label>
                                    <input type="text" class="form-control" id="codigo_barra" name="codigo_barra" value="<?php echo $r['codigo_barra']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $r['nombre']; ?>" required>
                                </div>
                               
                                <div class="form-group">
                                    <label for="nombre">Descripción</label>
                                    <textarea  class="form-control" type="text" name="descripcion" placeholder="descripción"  id="descripcion" rows="2"><?php if(!empty($r['descripcion'])) echo $r['descripcion'];?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="nombre">Precio</label>
                                    <input type="number" class="form-control" id="precio" name="precio" value="<?php echo $r['precio'];?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="nombre">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $r['stock']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="nombre">Foto</label>
                                    <input type="file" name="foto" id="foto" >
                                    <input type="hidden" name="foto_actual" id="foto_actual" value="<?php echo $r['foto'];?>">
                                </div>

                               
                                <div class="form-group">
                                    <label for="nombre">Categoría</label>
                                    <select name="id_categoria" id="id_categoria" class="form-control bg-light border-0 small" placeholder="Grupo"  aria-label="Grupo" aria-describedby="basic-addon2" style="margin-right: 1%;" required>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        $sql_g=mysqli_query($con,"select * from categorias order by nombre");
                                        if(mysqli_num_rows($sql_g)!=0)
                                        {
                                            while($r_g=mysqli_fetch_array($sql_g))
                                            {// el value es el id_categoria que paso por post al submitir ///////si id_categoria en categoria == id_categoria en productio entonces selecciono //////y muestro nombre de la categoria
                                                ?>
                                                <option value="<?php echo $r_g['id_categoria'];?>" <?php if($r_g['id_categoria']==$r['id_categoria']){?> selected <?php }?>><?php echo $r_g['nombre'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>                             
                            
                                <input type="hidden" name="id_producto" id="id_producto" value="<?php echo $r['id_producto']; ?>"> 
                                <button type="submit" class="btn btn-primary" style="float:right;">Guardar</button>
                            </form>
                            
                            </div>
                        </div>
                    </div>
            

           
            
         <!-- Page Heading -->
         <div class="card shadow mb-4 mx-auto" >
                        <div class="card-header py-3" id="headingTwo">
                        <h6 class="m-0 font-weight-bold text-primary" data-toggle="collapse" data-target="#collapseListado" aria-expanded="true" aria-controls="collapseListado">Productos</h6>
                        </div>
                        <div id="collapseListado" class="collapse <?php echo $showtable; ?>" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body" >
                             <div class="table-responsive" style="padding-right: 1% !important;">
                                    <table class="table table-bordered display nowrap" id="dataTable-mensajes" width="100%" cellspacing="0">
                                    <thead>
                                    
                                    <tr>
                                        <th>Codigo de Barras</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Foto</th>
                                        <th>Categoria</th>
                                        <th>Opciones</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Codigo de Barras</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Foto</th>
                                        <th>Categoria</th>
                                        <th>Opciones</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php $q=mysqli_query($con,"SELECT id_producto, foto, descripcion, P.Nombre as 'NombreP', P.Precio as 'PrecioP', P.stock as 'StockP', P.codigo_barra as 'codigo_barraP',
                                                                    C.Nombre as 'NonbreC' 
                                                                    FROM productos P 
                                                                    JOIN categorias C 
                                                                    ON P.id_categoria = C.id_categoria
                                                                    order by P.nombre;"); 
                                            if(mysqli_num_rows($q)!=0){
                                                while($r=mysqli_fetch_array($q)){?>
                                                 <tr>
                                                    <td><?php echo $r['codigo_barraP']; ?></td>
                                                     <td><?php echo $r['NombreP']; ?></td>
                                                     <td><?php echo $r['descripcion']; ?></td>

                                                     <td>$ <?php echo number_format($r['PrecioP'],2,',','.'); ?></td>
                                                     <td><?php echo number_format($r['StockP'],0,',','.'); ?></td>
                                                     
                                                     
                                                     <td>
                                                            <?php
                                                            if(file_exists("fotos/".$r['foto']) && !empty($r['foto']))
                                                            {
                                                                ?>
                                                                <img src="fotos/<?php echo $r['foto'];?>" width="50">
                                                                <?php
                                                            }
                                                            ?>
                                                    </td>
                                                    <td><?php echo $r['NonbreC']; ?></td>
                                                     <td>
                                                        <a href="home.php?pagina=productos&ver=<?php echo $r['id_producto'] ?>" title="Editar" alt="Editar"><i class="fas fa-edit icono_editar"></i></a> 
                                                        <a href="javascript:if(confirm('Esta Seguro?')){ window.location='home.php?pagina=productos&del=<?php echo $r['id_producto'] ?>'; }" title="Eliminar" alt="Eliminar"><i class="fas fa-eraser icono_borrar"></i></a>
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
