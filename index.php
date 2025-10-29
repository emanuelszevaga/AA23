<?php
session_start(); // Inicia la sesión para usar $_SESSION
include('includes/conexion.php');
conectar();

// Lógica para cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    desconectar(); // Desconecta antes de redirigir
    header("Location: index.php?success=sesion_cerrada");
    exit();
}

// Lógica para procesar el voto
if (isset($_GET['votar']) && isset($_SESSION['usuario_id'])) {
    $id_disfraz = intval($_GET['votar']);
    $id_usuario = $_SESSION['usuario_id'];

    // Verificar si el usuario ya votó ese disfraz
    $check = mysqli_query($con, "SELECT * FROM votos WHERE id_usuario=$id_usuario AND id_disfraz=$id_disfraz");
    
    // Si el voto no existe
    if (mysqli_num_rows($check) == 0) {
        // Registrar el voto
        mysqli_query($con, "INSERT INTO votos (id_usuario, id_disfraz) VALUES ($id_usuario, $id_disfraz)");
        // Sumar un voto al disfraz
        mysqli_query($con, "UPDATE disfraces SET votos = votos + 1 WHERE id=$id_disfraz");
        
        // APLICAR PRG y desconexión inmediata para la redirección exitosa
        desconectar(); 
        header("Location: index.php?success=voto_registrado");
        exit(); // Detiene la ejecución del script y garantiza la redirección
    } else {
        // Si el voto ya existe, redirige con un error
        desconectar();
        header("Location: index.php?error=voto_duplicado");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Concurso de disfraces de Halloween</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <?php if (!isset($_SESSION['usuario_id'])): // Ocultar si está logueado ?>
            <li><a href="#registro">Registro</a></li>
            <li><a href="#login">Iniciar Sesión</a></li>
            <?php else: ?>
            <li><a href="index.php?logout=true">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php endif; ?>
            
            <?php 
            // Mostrar link a Admin si el usuario logueado es el admin (ID 1, según la lógica de admin.php)
            if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == 1): 
            ?>
            <li><a href="admin.php">Panel de Administración</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <header>
        <h1>Concurso de disfraces de Halloween</h1>
    </header>
    <main>
        
        <?php
        // Mostrar mensajes de éxito o error al usuario (redirigidos desde login/registro/etc.)
        if (isset($_GET['success'])) {
            echo "<div style='background-color: #4CAF50; color: white; padding: 10px; margin: 10px auto; width: 80%; max-width: 400px; text-align: center; border-radius: 5px;'>";
            if ($_GET['success'] == 'registro_exitoso') {
                echo "✅ ¡Registro exitoso! Por favor, inicia sesión.";
            } elseif ($_GET['success'] == 'login_exitoso') {
                echo "✅ ¡Bienvenido " . htmlspecialchars($_SESSION['username']) . "!";
            } elseif ($_GET['success'] == 'disfraz_agregado') {
                echo "✅ Disfraz agregado con éxito.";
            } elseif ($_GET['success'] == 'voto_registrado') {
            echo "✅ ¡Voto registrado con éxito! Gracias por participar.";
            } elseif ($_GET['success'] == 'sesion_cerrada') {
            // ...
            }
            echo "</div>";
        }
        if (isset($_GET['error'])) {
            echo "<div style='background-color: #f44336; color: white; padding: 10px; margin: 10px auto; width: 80%; max-width: 400px; text-align: center; border-radius: 5px;'>";
            if ($_GET['error'] == 'login_invalido') {
                echo "⚠️ Usuario o contraseña incorrectos.";
            } elseif ($_GET['error'] == 'usuario_existente') {
                echo "⚠️ El nombre de usuario ya está en uso.";
            } elseif ($_GET['error'] == 'acceso_admin_denegado') {
                echo "⚠️ Acceso denegado. Solo el administrador puede entrar al panel.";
            } else {
                echo "⚠️ Ocurrió un error. Código: " . htmlspecialchars($_GET['error']); 
            }
            echo "</div>";
        }
        ?>

        <section id="disfraces-list" class="section">
            <h2>Disfraces disponibles</h2>

            <?php
            global $con;

            // Obtener disfraces activos
            $resultado = mysqli_query($con, "SELECT * FROM disfraces WHERE eliminado=0");

            if (mysqli_num_rows($resultado) > 0) {
                while ($row = mysqli_fetch_assoc($resultado)) {
                    echo "<div class='disfraz'>";
                    echo "<h2>" . htmlspecialchars($row['nombre']) . "</h2>";
                    echo "<p>" . htmlspecialchars($row['descripcion']) . "</p>";

                    // Si tenés fotos en 'imagenes/', usá esto:
                    echo "<img src='imagenes/" . htmlspecialchars($row['foto']) . "' width='100%'>";

                    echo "<p>🗳️ Votos: " . $row['votos'] . "</p>";

                    // Mostrar botón de voto solo si el usuario está logueado
                    if (isset($_SESSION['usuario_id'])) {
                        // El botón de votar se enlaza a index.php?votar=[id]
                        echo "<a href='index.php?votar=" . $row['id'] . "'><button class='votar'>Votar</button></a>";
                    } else {
                        echo "<p><em>Inicia sesión para votar.</em></p>";
                    }

                    echo "</div><hr>";
                }
            } else {
                echo "<p>No hay disfraces cargados todavía.</p>";
            }
            ?>
        </section>
        
        <?php if (!isset($_SESSION['usuario_id'])): // Mostrar si NO está logueado ?>
        
        <section id="registro" class="section">
            <h2>Registro</h2>
            <form action="registro.php" method="POST">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Registrarse</button>
            </form>
        </section>
        <section id="login" class="section">
            <h2>Iniciar Sesión</h2>
            <form action="login.php" method="POST">
                <label for="login-username">Nombre de Usuario:</label>
                <input type="text" id="login-username" name="login-username" required>
                
                <label for="login-password">Contraseña:</label>
                <input type="password" id="login-password" name="login-password" required>
                
                <button type="submit">Iniciar Sesión</button>
            </form>
        </section>
        
        <?php endif; ?>
        
        <section id="admin" class="section">
            <h2>Agregar Disfraz</h2>
            <form action="procesar_disfraz.php" method="POST" enctype="multipart/form-data">
                <label for="disfraz-nombre">Nombre del Disfraz:</label>
                <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>
                
                <label for="disfraz-descripcion">Descripción del Disfraz:</label>
                <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>
                
                <label for="disfraz-foto">Foto:</label>
                <input type="file" id="disfraz-foto" name="disfraz-foto" required>

                <button type="submit">Agregar Disfraz</button>
            </form>
        </section>
    </main>
    <script src="js/script.js"></script>
    <?php desconectar(); ?> 
</body>
</html>