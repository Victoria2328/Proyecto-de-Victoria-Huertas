<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
require_once "conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nombre = "";

// SI ES EDICIÓN → CARGAR DATOS
if ($id > 0) {
    $stmt = $conn->prepare("SELECT nombre FROM tipos_productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();
}

// GUARDAR (CREAR O EDITAR)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];

    if ($id > 0) {
        // EDITAR
        $stmt = $conn->prepare("UPDATE tipos_productos SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre, $id);
    } else {
        // CREAR
        $stmt = $conn->prepare("INSERT INTO tipos_productos (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
    }

    $stmt->execute();
    $stmt->close(); // cerrar statement
    header("Location: tipos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= $id > 0 ? "Editar Tipo" : "Nuevo Tipo" ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Lato:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
/* -------------------------------------- */
/* --- 1. Variables Monocromáticas --- */
/* -------------------------------------- */
:root {
    --color-fondo-claro: #FFFFFF;     /* Blanco puro para el fondo principal */
    --color-fondo-oscuro: #0A0A0A;    /* Negro casi puro para el fondo del body */
    --color-fondo-intermedio: #F5F5F5; /* Gris muy claro para elementos sutiles */
    --color-acento-negro: #000000;    /* Negro puro para botones y títulos */
    --color-acento-hover: #333333;    /* Gris muy oscuro para hover */
    --color-texto-principal: #212121; /* Texto oscuro */
    --color-texto-secundario: #616161; /* Gris oscuro para labels */
    --color-borde-gris: #E0E0E0;      /* Gris claro para bordes y separaciones */
    --sombra-fuerte: 0 10px 40px rgba(0, 0, 0, 0.4);
}

body {
    background: var(--color-fondo-oscuro);
    font-family: 'Lato', sans-serif;
    margin: 0;
    padding: 30px;
    color: var(--color-texto-principal);
}

/* -------------------------------------- */
/* --- 2. Contenedor del Formulario --- */
/* -------------------------------------- */
.form-box {
    background: var(--color-fondo-claro);
    padding: 40px;
    border-radius: 18px;
    max-width: 500px;
    margin: 50px auto;
    /* Sombra elegante sobre fondo negro */
    box-shadow: var(--sombra-fuerte);
    border: 1px solid var(--color-borde-gris);
    transition: all 0.3s ease-in-out;
}
.form-box:hover {
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
}

h3 {
    font-family: 'Montserrat', sans-serif;
    color: var(--color-acento-negro);
    margin-bottom: 30px;
    text-align: center;
    font-weight: 700;
    font-size: 2em;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* -------------------------------------- */
/* --- 3. Inputs y Labels --- */
/* -------------------------------------- */
label {
    font-family: 'Lato', sans-serif;
    font-weight: 600;
    color: var(--color-texto-secundario);
    display: block;
    margin-bottom: 8px;
    font-size: 0.95em;
    text-transform: uppercase;
}

input.form-control {
    background: var(--color-fondo-intermedio);
    color: var(--color-texto-principal);
    border: 1px solid var(--color-borde-gris);
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease-in-out;
}

input.form-control:focus {
    border-color: var(--color-acento-negro);
    /* Sombra de enfoque negra sutil */
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.15);
    background: var(--color-fondo-claro);
    outline: none;
}

/* -------------------------------------- */
/* --- 4. Botones --- */
/* -------------------------------------- */
.btn-save {
    background: var(--color-acento-negro);
    border: none;
    color: var(--color-fondo-claro);
    font-weight: 700;
    border-radius: 10px;
    padding: 14px 25px;
    cursor: pointer;
    margin-top: 25px;
    width: 100%;
    transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.btn-save:hover {
    background: var(--color-acento-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
}

.btn-back {
    background: var(--color-fondo-intermedio);
    border: 1px solid var(--color-borde-gris);
    color: var(--color-texto-secundario);
    font-weight: 600;
    border-radius: 10px;
    padding: 12px 20px;
    margin-top: 10px;
    text-decoration: none;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    width: 100%;
    transition: background 0.3s, color 0.3s;
}

.btn-back:hover {
    background: var(--color-borde-gris);
    color: var(--color-texto-principal);
}
</style>

</head>

<body>
<div class="form-box">

    <h3 class="mb-3">
        <i class="fas fa-edit"></i> 
        <?= $id > 0 ? "EDITAR TIPO" : "NUEVO TIPO" ?>
    </h3>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre del Tipo</label>
            <input type="text" name="nombre" class="form-control" 
                   value="<?= htmlspecialchars($nombre) ?>" 
                   placeholder="Ej: Sedan, SUV, Deportivo"
                   required>
        </div>

        <button class="btn btn-save" type="submit">
            <i class="fas fa-<?= $id > 0 ? "save" : "plus-circle" ?>"></i>
            <?= $id > 0 ? "GUARDAR CAMBIOS" : "CREAR NUEVO TIPO" ?>
        </button>
        <a href="tipos.php" class="btn btn-back">
            <i class="fas fa-chevron-left"></i> 
            VOLVER A TIPOS
        </a>
    </form>

</div>
</body>
</html>