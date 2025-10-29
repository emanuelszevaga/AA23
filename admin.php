<?php
session_start();
include('includes/conexion.php');
conectar();

// ADMIN CHECK: Verifica que el usuario esté logueado y sea el administrador (ID 1)
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_id'] != 1) {
    header("Location: index.php?error=acceso_admin_denegado");
    desconectar();
    exit();
}

// Lógica para ELIMINAR un Disfraz (Soft Delete)
if (isset($_GET['delete'])) {
    $id_disfraz = intval($_GET['delete']);
    global $con;
    $query = "UPDATE disfraces SET eliminado=1 WHERE id=$id_disfraz";
    mysqli_query($con, $query);
    header("Location: admin.php?success=disfraz_eliminado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Panel de Administración</title>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <nav>
            <ul>
                <li><a href="index.php">Volver al Inicio</a></li>
                <li><a href="index.php?logout=true">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <header>
            <h1>Panel de Administración de Disfraces</h1>
        </header>
        <main>
            <section id="disfraces-admin" class="section" style="max-width: 800px;">
                <h2>Gestión de Disfraces (CRUD)</h2>

                <?php
                // Mostrar mensajes de éxito/error
                if (isset($_GET['success'])) {
                    echo "<p style='color:green;'>✅ Operación exitosa: " . htmlspecialchars($_GET['success']) . "</p>";
                }
                if (isset($_GET['error'])) {
                    echo "<p style='color:red;'>⚠️ Error: " . htmlspecialchars($_GET['error']) . "</p>";
                }

                global $con;
                // Obtener TODOS los disfraces (para gestión)
                $resultado = mysqli_query($con, "SELECT * FROM disfraces");

                if (mysqli_num_rows($resultado) > 0) {
                    echo "<table border='1' style='width: 100%; color: #fff; border-color: #FF5722; text-align: left; border-collapse: collapse;'>";
                    echo "<tr><th style='padding: 10px;'>ID</th><th style='padding: 10px;'>Nombre</th><th style='padding: 10px;'>Votos</th><th style='padding: 10px;'>Estado</th><th style='padding: 10px;'>Acciones</th></tr>";

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        $estado = ($row['eliminado'] == 0) ? 'Activo' : 'Eliminado (Soft Delete)';
                        echo "<tr>";
                        echo "<td style='padding: 10px;'>" . $row['id'] . "</td>";
                        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td style='padding: 10px;'>" . $row['votos'] . "</td>";
                        echo "<td style='padding: 10px;'>" . $estado . "</td>";
                        echo "<td style='padding: 10px;'>";
                        echo "<a href='admin.php?edit=" . $row['id'] . "'><button style='width: auto; padding: 5px;'>Editar</button></a> ";
                        if ($row['eliminado'] == 0) {
                            echo "<a href='admin.php?delete=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de eliminar (marcar como inactivo) este disfraz?\")'><button style='width: auto; padding: 5px; background-color: #D84315;'>Eliminar</button></a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No hay disfraces en la base de datos.</p>";
                }
                ?>
            </section>

            <?php
            if (isset($_GET['edit'])) {
                $id_edit = intval($_GET['edit']);
                $edit_query = mysqli_query($con, "SELECT * FROM disfraces WHERE id=$id_edit");
                if (mysqli_num_rows($edit_query) == 1) {
                    $disfraz_edit = mysqli_fetch_assoc($edit_query);
                    ?>
                    <section id="edit-form" class="section" style="max-width: 800px;">
                        <h2>Editar Disfraz #<?php echo $disfraz_edit['id']; ?></h2>
                        <form action="procesar_disfraz.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id-disfraz" value="<?php echo $disfraz_edit['id']; ?>">
                            <input type="hidden" name="action" value="update">
                            
                            <label for="edit-nombre">Nombre:</label>
                            <input type="text" id="edit-nombre" name="nombre" value="<?php echo htmlspecialchars($disfraz_edit['nombre']); ?>" required>
                            
                            <label for="edit-descripcion">Descripción:</label>
                            <textarea id="edit-descripcion" name="descripcion" required><?php echo htmlspecialchars($disfraz_edit['descripcion']); ?></textarea>
                            
                            <label for="edit-foto">Cambiar Foto (Opcional):</label>
                            <input type="file" id="edit-foto" name="foto">
                            <p>Foto actual: <?php echo htmlspecialchars($disfraz_edit['foto']); ?></p>

                            <button type="submit">Guardar Cambios</button>
                        </form>
                    </section>
                    <?php
                }
            }
            ?>
            
            <?php
            if (isset($_GET['logout'])) {
                session_destroy();
                header("Location: index.php?success=logout");
                exit();
            }
            ?>
        </main>
        <?php desconectar(); ?>
    </body>
</html>