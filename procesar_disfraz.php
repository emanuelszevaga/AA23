<?php
session_start();
include('includes/conexion.php');
conectar();

// Verifica si el usuario está logueado (solo usuarios logueados pueden añadir/editar)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=permiso_denegado");
    desconectar();
    exit();
}

global $con;

// Lógica para agregae un disfraz (Formulario de index.php)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    
    // Recoge los datos
    $nombre = mysqli_real_escape_string($con, $_POST['disfraz-nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['disfraz-descripcion']);
    
    // Manejo de la subida de archivos
    $upload_dir = 'imagenes/'; 
    $foto_temp_name = $_FILES['disfraz-foto']['tmp_name'];
    $foto_file_name = $_FILES['disfraz-foto']['name'];
    
    // Genera un nombre único para la foto
    $foto_unique_name = time() . '_' . basename($foto_file_name);
    $upload_file = $upload_dir . $foto_unique_name;

    if (move_uploaded_file($foto_temp_name, $upload_file)) {
        
        // Inserta el disfraz
        $query = "INSERT INTO disfraces (nombre, descripcion, foto, votos, eliminado, foto_blob) 
                VALUES ('$nombre', '$descripcion', '$foto_unique_name', 0, 0, '')";
        
        if (mysqli_query($con, $query)) {
            header("Location: index.php?success=disfraz_agregado#disfraces-list");
        } else {
            // Elimina el archivo subido si falla la DB
            unlink($upload_file); 
            header("Location: index.php?error=db_insert_fallido");
        }

    } else {
        header("Location: index.php?error=subida_fallida");
    }

}

// Lógica para editar/actualizar un disfraz (Formulario de admin.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    
    // Asume que el usuario con ID 1 es el administrador (Ver admin.php)
    if ($_SESSION['usuario_id'] != 1) {
        header("Location: index.php?error=permiso_admin_denegado");
        desconectar();
        exit();
    }

    $id_disfraz = intval($_POST['id-disfraz']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    
    // Base query para update (actualiza texto)
    $query = "UPDATE disfraces SET nombre='$nombre', descripcion='$descripcion' WHERE id=$id_disfraz";
    
    // Manejo de foto opcional
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        
        $upload_dir = 'imagenes/'; 
        $foto_temp_name = $_FILES['foto']['tmp_name'];
        $foto_file_name = $_FILES['foto']['name'];
        $foto_unique_name = time() . '_' . basename($foto_file_name);
        $upload_file = $upload_dir . $foto_unique_name;

        if (move_uploaded_file($foto_temp_name, $upload_file)) {
            // Actualiza la query para incluir la nueva foto
            $query = "UPDATE disfraces SET nombre='$nombre', descripcion='$descripcion', foto='$foto_unique_name' WHERE id=$id_disfraz";
        } else {
            // Falla al mover la foto, pero se continúa con el update de texto
        }
    }

    if (mysqli_query($con, $query)) {
        header("Location: admin.php?success=disfraz_actualizado");
    } else {
        header("Location: admin.php?error=db_update_fallido");
    }
}

desconectar();
?>