<?php
require "conexion.php";
session_start();
if (!isset($_SESSION["user"])) { header("Location: login.php"); exit; }

$id = $_GET["id"] ?? null;

if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $conn->query("DELETE FROM tipos_productos WHERE id=$id");
    header("Location: categorias.php");
    exit;
}

$nombre = "";

if ($id) {
    $q = $conn->query("SELECT * FROM tipos_productos WHERE id=$id");
    $data = $q->fetch_assoc();
    $nombre = $data["nombre"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];

    if ($id) {
        $conn->query("UPDATE tipos_productos SET nombre='$nombre' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO tipos_productos(nombre) VALUES('$nombre')");
    }

    header("Location: categorias.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h3><?= $id ? "Editar" : "Nueva" ?> categoría</h3>

    <form method="POST">
        <label>Nombre:</label>

        <input class="form-control mb-3" name="nombre"
               value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>" required>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="categorias.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>

</body>
</html>
