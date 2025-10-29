<?php
session_start();
include('includes/conexion.php');
conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = $_POST['login-username'];
    $password = $_POST['login-password'];

    if (empty($username) || empty($password)) {
        header("Location: index.php?error=login_campos_vacios#login");
        desconectar();
        exit();
    }

    global $con;
    $username_safe = mysqli_real_escape_string($con, $username);

    // Busca usuario
    $query = "SELECT id, nombre, clave FROM usuarios WHERE nombre = '$username_safe'";
    $resultado = mysqli_query($con, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        // Verifica la contraseña con el hash almacenado
        if (password_verify($password, $usuario['clave'])) {
            
            // Autenticación exitosa: Establecer sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['nombre'];
            
            // Redirige al índice
            header("Location: index.php?success=login_exitoso");
            
        } else {
            // Contraseña incorrecta
            header("Location: index.php?error=login_invalido#login");
        }

    } else {
        // Usuario no encontrado
        header("Location: index.php?error=login_invalido#login");
    }
} else {
    header("Location: index.php");
}

desconectar();
?>