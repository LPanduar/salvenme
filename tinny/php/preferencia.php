<?php
global $pdo;
require 'database.php';

// Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
session_start();
$usuario_id = $_SESSION['usuario_id']; // Asegúrate de que `usuario_id` esté en la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $sexo = $_POST['sexo'];
    $edad = $_POST['edad'];
    $categoria = $_POST['categoria'];
    $usuario_id = $_SESSION['usuario_id']; // Asumiendo que el ID del usuario se guarda en la sesión

    // Manejar la subida de la foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $ruta_foto = "imagenes/" . basename($foto);
        move_uploaded_file($foto_tmp, $ruta_foto);
    } else {
        $ruta_foto = NULL;
    }

    // Actualizar las preferencias del usuario
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, sexo = ?, edad = ?, foto = ?, categoria_id = ? WHERE id = ?");
        $stmt->execute([$nombre, $sexo, $edad, $ruta_foto, $categoria, $usuario_id]);
        echo "Preferencias actualizadas exitosamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar las preferencias: " . $e->getMessage();
    }
}
?>

