<?php
include('includes/conexion.php');
conectar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Concurso de disfraces de Halloween</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <li><a href="#registro">Registro</a></li>
            <li><a href="#login">Iniciar Sesión</a></li>
            <li><a href="#admin">Panel de Administración</a></li>
        </ul>
    </nav>
    <header>
        <h1>Concurso de disfraces de Halloween</h1>
    </header>
    <main>
        <section id="disfraces-list" class="section">
            <h2>Disfraces disponibles</h2>

            <?php
            global $con;

            // Si el usuario hace clic en "Votar"
            if (isset($_GET['votar']) && isset($_SESSION['usuario_id'])) {
                $id_disfraz = intval($_GET['votar']);
                $id_usuario = $_SESSION['usuario_id'];

                // Verificar si el usuario ya votó ese disfraz
                $check = mysqli_query($con, "SELECT * FROM votos WHERE id_usuario=$id_usuario AND id_disfraz=$id_disfraz");
                if (mysqli_num_rows($check) == 0) {
                    // Registrar el voto
                    mysqli_query($con, "INSERT INTO votos (id_usuario, id_disfraz) VALUES ($id_usuario, $id_disfraz)");
                    // Sumar un voto al disfraz
                    mysqli_query($con, "UPDATE disfraces SET votos = votos + 1 WHERE id=$id_disfraz");
                    echo "<p style='color:green;'>✅ ¡Voto registrado con éxito!</p>";
                } else {
                    echo "<p style='color:red;'>⚠️ Ya votaste este disfraz.</p>";
                }
            }

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
        <section id="registro" class="section">
            <h2>Registro</h2>
            <form action="procesar_registro.php" method="POST">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Registrarse</button>
            </form>
        </section>
        <section id="login" class="section">
            <h2>Iniciar Sesión</h2>
            <form action="procesar_login.php" method="POST">
                <label for="login-username">Nombre de Usuario:</label>
                <input type="text" id="login-username" name="login-username" required>
                
                <label for="login-password">Contraseña:</label>
                <input type="password" id="login-password" name="login-password" required>
                
                <button type="submit">Iniciar Sesión</button>
            </form>
        </section>
        <section id="admin" class="section">
            <h2>Panel de Administración</h2>
            <form action="procesar_disfraz.php" method="POST">
                <label for="disfraz-nombre">Nombre del Disfraz:</label>
                <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>
                
                <label for="disfraz-descripcion">Descripción del Disfraz:</label>
                <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>
                
                <label for="disfraz-nombre">Foto:</label>
                <input type="file" id="disfraz-foto" name="disfraz-foto" required>

                <button type="submit">Agregar Disfraz</button>
            </form>
        </section>
    </main>
    <script src="js/script.js"></script>
    <?php desconectarBD(); ?>
</body>
</html>
