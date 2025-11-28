<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

require_once "conexion.php";

// ELIMINAR
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM tipos_productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: tipos.php");
    exit;
}

// LISTAR
$result = $conn->query("SELECT * FROM tipos_productos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Tipos de Productos ≽^• ˕ • ྀི≼- Monocromático</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Lato:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
/* -------------------------------------- */
/* --- 1. Variables Monocromáticas --- */
/* -------------------------------------- */
:root {
    --color-fondo-claro: #FFFFFF;     /* Blanco puro para el fondo principal */
    --color-fondo-oscuro: #0A0A0A;    /* Negro casi puro (Usado en el dashboard, aquí no en body) */
    --color-fondo-intermedio: #F5F5F5; /* Gris muy claro para elementos sutiles */
    --color-acento-negro: #000000;    /* Negro puro para botones principales y títulos */
    --color-acento-hover: #333333;    /* Gris muy oscuro para hover de acento */
    --color-texto-principal: #212121; /* Texto oscuro */
    --color-texto-secundario: #616161; /* Gris oscuro para headers */
    --color-borde-gris: #E0E0E0;      /* Gris claro para bordes */
    --color-peligro: #D32F2F;         /* Rojo de peligro estándar (fuera de la escala de grises, pero necesario) */
    --color-peligro-hover: #C62828;
    --sombra-suave: 0 4px 12px rgba(0, 0, 0, 0.08); /* Sombra sutil */
}

body { 
    background: var(--color-fondo-intermedio); /* Fondo gris claro */
    font-family: 'Lato', sans-serif; 
    color: var(--color-texto-principal);
}

/* -------------------------------------- */
/* --- 2. Contenedor de la Tabla --- */
/* -------------------------------------- */
.table-box {
    background: var(--color-fondo-claro); 
    padding: 35px; /* Más padding para sensación premium */
    border-radius: 18px;
    max-width: 900px; 
    margin: auto; 
    margin-top: 50px;
    box-shadow: var(--sombra-suave);
    border: 1px solid var(--color-borde-gris);
    transition: box-shadow 0.3s ease-in-out;
}
.table-box:hover {
    box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
}

h3 {
    font-family: 'Montserrat', sans-serif;
    color: var(--color-acento-negro);
    font-weight: 800;
    font-size: 2.2em;
    text-transform: uppercase;
}

/* -------------------------------------- */
/* --- 3. Botones de Navegación --- */
/* -------------------------------------- */
.d-flex.gap-2 {
    gap: 15px !important;
}

/* Botón Nuevo Tipo (Acento principal - Negro) */
.btn-new {
    background: var(--color-acento-negro);
    border: none; 
    color: var(--color-fondo-claro);
    font-weight: 700; 
    border-radius: 10px; 
    padding: 10px 20px;
    transition: all 0.2s ease-in-out;
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-new:hover {
    background: var(--color-acento-hover);
    transform: translateY(-1px);
}

/* Botón Inicio (Secundario - Gris) */
.btn-home {
    background: var(--color-fondo-intermedio); 
    border: 1px solid var(--color-borde-gris); 
    color: var(--color-texto-secundario);
    font-weight: 600; 
    border-radius: 10px; 
    padding: 10px 20px;
    transition: all 0.2s ease-in-out;
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-home:hover {
    background: var(--color-borde-gris);
    color: var(--color-texto-principal);
}

/* -------------------------------------- */
/* --- 4. Tabla y Acciones --- */
/* -------------------------------------- */

.table-hover > tbody > tr:hover > * {
    background-color: var(--color-fondo-intermedio);
    color: var(--color-acento-negro);
}

.table {
    border-collapse: separate;
    border-spacing: 0 5px; /* Espacio entre filas */
}

thead tr {
    color: var(--color-texto-secundario) !important;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.9em;
    border-bottom: 2px solid var(--color-borde-gris);
}

tbody tr {
    border-top: 8px solid var(--color-fondo-claro); /* Separación visual en el fondo blanco */
    background: var(--color-fondo-claro);
}

/* Botón Editar (Gris Oscuro) */
.btn-edit {
    background: var(--color-texto-secundario); 
    border: none; 
    color: var(--color-fondo-claro);
    font-weight: 600; 
    border-radius: 8px; 
    padding: 8px 15px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-edit:hover {
    background: var(--color-acento-hover);
}

/* Botón Eliminar (Rojo de Peligro) */
.btn-delete {
    background: var(--color-peligro); 
    border: none; 
    color: var(--color-fondo-claro);
    font-weight: 600; 
    border-radius: 8px; 
    padding: 8px 15px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-delete:hover {
    background: var(--color-peligro-hover);
}
</style>

</head>
<body>

<div class="table-box">

    <div class="d-flex justify-content-between mb-4 align-items-center">

        <h3 class="mt-2"><i class="fas fa-tags"></i> Tipos de Productos</h3>

        <div class="d-flex gap-2">
            <a href="dashboard.php" class="btn btn-home">
                <i class="fas fa-home"></i> Dashboard
            </a>

            <a href="tipos_form.php" class="btn btn-new">
                <i class="fas fa-plus-circle"></i> Nuevo Tipo
            </a>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>

                <td>
                    <a href="tipos_form.php?id=<?= $row['id'] ?>" class="btn btn-edit">
                        <i class="fas fa-pen"></i> Editar
                    </a>

                    <a href="tipos.php?delete=<?= $row['id'] ?>" 
                        class="btn btn-delete"
                        onclick="return confirm('¿Seguro que deseas eliminar este tipo? Esta acción no se puede deshacer.')">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
    
    <?php if ($result->num_rows == 0): ?>
    <div class="text-center p-4 text-secondary">
        <i class="fas fa-info-circle"></i> No hay tipos de productos registrados aún.
    </div>
    <?php endif; ?>

</div>

</body>
</html>