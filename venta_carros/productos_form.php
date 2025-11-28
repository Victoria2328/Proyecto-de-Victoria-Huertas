<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
require_once "conexion.php";

$id = 0;
$nombre = "";
$marca = "";
$precio = "";
$tipo_id = "";

// Cargar tipos
$tipos = $conn->query("SELECT * FROM tipos_productos ORDER BY nombre ASC");

// EDITAR
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $nombre = $row["nombre"];
        $marca = $row["marca"];
        $precio = $row["precio"];
        $tipo_id = $row["tipo_id"];
    }
}

// GUARDAR
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = intval($_POST["id"]);
    $nombre = $_POST["nombre"];
    $marca = $_POST["marca"];
    $precio = intval($_POST["precio"]);
    $tipo_id = intval($_POST["tipo_id"]);

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, marca=?, precio=?, tipo_id=? WHERE id=?");
        $stmt->bind_param("ssiii", $nombre, $marca, $precio, $tipo_id, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO productos (nombre, marca, precio, tipo_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nombre, $marca, $precio, $tipo_id);
    }

    $stmt->execute();
    header("Location: productos.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Guardar Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background:#FAF8FF; font-family:'Poppins',sans-serif; }

    .card {
        max-width:600px;margin:40px auto;background:white;padding:25px;
        border-radius:18px;border:1px solid #E6DDFF;
    }

    h2 { color:#7A5DFD;text-align:center;font-weight:600;margin-bottom:20px; }

    .btn-primary { background:#C8BBFF;border:none;font-weight:600; }
    .btn-light { background:#ECE7FF;border:none;font-weight:600; }
</style>
</head>

<body>

<div class="card">
    <h2><?= $id > 0 ? "Editar Producto" : "Nuevo Producto" ?></h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control mb-3" value="<?= $nombre ?>" required>

        <label>Marca</label>
        <input type="text" name="marca" class="form-control mb-3" value="<?= $marca ?>" required>

        <label>Precio</label>
        <input type="number" name="precio" class="form-control mb-3" value="<?= $precio ?>" required>

        <label>Tipo de Producto</label>
        <select name="tipo_id" class="form-control mb-3" required>
            <option value="">Seleccione...</option>

            <?php while($t = $tipos->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>" <?= $t['id']==$tipo_id ? "selected":"" ?>>
                    <?= $t['nombre'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button class="btn btn-primary w-100 mb-2">Guardar</button>
        <a href="productos.php" class="btn btn-light w-100">Cancelar</a>
    </form>
</div>

</body>
</html>
