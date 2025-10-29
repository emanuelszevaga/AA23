<?php
session_start();
include('includes/conexion.php');
conectar();

// Verifica que la solicitud sea por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: index.php?error=campos_vacios#registro");
        desconectar();
        exit();
    }

    global $con;
    $username_safe = mysqli_real_escape_string($con, $username);

    // Se hashea la contraseña para no guardarla en texto plano.
    $password_hashed = password_hash($password, PASSWORD_DEFAULT); 

    // Verifica si el usuario ya existe
    $check_query = "SELECT id FROM usuarios WHERE nombre = '$username_safe'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: index.php?error=usuario_existente#registro");
        desconectar();
        exit();
    }

    // Inserta nuevo usuario
    $insert_query = "INSERT INTO usuarios (nombre, clave) VALUES ('$username_safe', '$password_hashed')";
    
    if (mysqli_query($con, $insert_query)) {
        // Redirige a iniciar sesión con éxito
        header("Location: index.php?success=registro_exitoso#login");
    } else {
        header("Location: index.php?error=db_registro_fallido#registro");
    }
} else {
    header("Location: index.php");
}

desconectar();
?>