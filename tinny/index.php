<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Red Social de Encuestas</title>
</head>
<body>
<div class="navbar">
    <a href="./index.php">Inicio</a>
    <a href="./buscar_encuestas.php">Buscar Encuestas</a>
    <a href="./preferencias.php">Preferencias</a>
    <a href="./php/logout.php">Cerrar Sesión</a>
    <a href="./perfil.php">Perfil</a>
</div>

<div class="container">
    <h2>Bienvenido a la Red Tinny</h2>
    <!-- Contenido Principal -->

    <?php
    global $conn;
    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "tinny";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
    session_start();
    $usuario_id = $_SESSION['usuario_id']; // Asegúrate de que `usuario_id` esté en la sesión

    // Consulta para obtener las encuestas
    $sql = "SELECT id, nombre, pregunta FROM encuestas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mostrar cada encuesta
        while($row = $result->fetch_assoc()) {
            echo "<div class='encuesta'>";
            echo "<h3>" . $row["nombre"] . "</h3>";
            echo "<p>" . $row["pregunta"] . "</p>";

            // Consulta para obtener las opciones de la encuesta actual
            $sql_opciones = "SELECT id, opcion, votos FROM opciones WHERE encuesta_id = " . $row["id"];
            $result_opciones = $conn->query($sql_opciones);

            if ($result_opciones->num_rows > 0) {
                echo "<form method='post' action='./php/votar.php'>";
                while($row_opcion = $result_opciones->fetch_assoc()) {
                    echo "<div class='opcion'>";
                    echo "<input type='radio' name='opcion' value='" . $row_opcion["id"] . "'>";
                    echo "<label>" . $row_opcion["opcion"] . " (" . $row_opcion["votos"] . " votos)</label>";
                    echo "</div>";
                }
                echo "<input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>";
                echo "<input type='submit' value='Votar'>";
                echo "</form>";
            } else {
                echo "No hay opciones disponibles para esta encuesta.";
            }

            echo "</div>";
        }
    } else {
        echo "No hay encuestas disponibles.";
    }

    // Cerrar conexión
    $conn->close();
    ?>
</div>
</body>
</html>
