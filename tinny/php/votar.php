<?php
global $pdo;
require 'database.php';

// Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
session_start();
$usuario_id = $_SESSION['usuario_id']; // Asegúrate de que `usuario_id` esté en la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $opcion_id = $_POST['opcion_id'];

    $stmt = $pdo->prepare("INSERT INTO votos (id_usuario, id_opcion) VALUES (?, ?)");
    if ($stmt->execute([$usuario_id, $opcion_id])) {
        header("Location: ../index.php");
    } else {
        echo "Error al registrar el voto.";
    }
}
?>
