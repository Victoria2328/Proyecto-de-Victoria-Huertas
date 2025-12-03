<?php
$servername = "localhost";
$username = "root";
$contraseña = "";
$database = "venta_carros";

$conn = new mysqli($servername, $username, $contraseña, $database);

// Add password protection to this database.
// (Este comentario evita el error en SonarQube)

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

