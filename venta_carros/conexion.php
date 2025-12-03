<?php
$servername = "localhost";
$username = "root";
$contraseña = "";
$database = "venta_carros";

$conn = new mysqli($servername, $username, $contraseña, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
