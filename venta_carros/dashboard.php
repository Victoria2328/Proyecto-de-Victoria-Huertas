<?php
// ... (Tu código PHP se mantiene idéntico)

session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// ===============================
// OBTENER TIPOS DE PRODUCTOS
// ===============================
$tipos = $conn->query("SELECT * FROM tipos_productos");

// ===============================
// CREAR / EDITAR PRODUCTO
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $tipo_id = $_POST['tipo_id'] ?? 0;
    $descripcion = $_POST['descripcion'] ?? '';

    if ($id === '') {
        // INSERTAR
        $stmt = $conn->prepare("INSERT INTO productos (nombre, marca, precio, tipo_id, descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $nombre, $marca, $precio, $tipo_id, $descripcion);
        $stmt->execute();
    } else {
        // ACTUALIZAR
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, marca=?, precio=?, tipo_id=?, descripcion=? WHERE id=?");
        $stmt->bind_param("ssdisi", $nombre, $marca, $precio, $tipo_id, $descripcion, $id);
        $stmt->execute();
    }

    header("Location: dashboard.php");
    exit;
}

// ==================================
// ELIMINAR
// ==================================
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM productos WHERE id = $id");
    header("Location: dashboard.php");
    exit;
}

// ==================================
// OBTENER PRODUCTO PARA EDICIÓN
// ==================================
$producto_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto_editar = $resultado->fetch_assoc();
    $stmt->close();
}


// ==================================
// OBTENER PRODUCTOS PARA LA TABLA
// ==================================
// Volver a obtener tipos ya que el query anterior ($tipos) fue consumido en el formulario
$tipos->data_seek(0); 

$productos = $conn->query("
    SELECT p.*, t.nombre AS tipo
    FROM productos p
    INNER JOIN tipos_productos t ON t.id = p.tipo_id
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Monocromático</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Lato:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* -------------------------------------- */
        /* --- 1. Variables y Estilos Generales --- */
        /* -------------------------------------- */
        :root {
            --color-fondo-claro: #FFFFFF; /* Blanco puro para el fondo principal */
            --color-fondo-intermedio: #F8F8F8; /* Gris muy claro para elementos sutiles */
            --color-texto-oscuro: #212121; /* Negro casi puro para texto principal */
            --color-texto-medio: #616161; /* Gris oscuro para texto secundario/labels */
            --color-borde-gris: #E0E0E0; /* Gris claro para bordes */
            --color-acento-negro: #000000; /* Negro puro para acentos y botones */
            --color-acento-hover: #333333; /* Gris muy oscuro para hover de acento */
            --color-peligro: #D32F2F; /* Rojo clásico para peligro */
            --color-peligro-hover: #C62828;
            
            /* Sombra sutil para un aspecto elevado */
            --sombra-suave: 0 4px 12px rgba(0, 0, 0, 0.05);
            --sombra-media: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--color-fondo-claro);
            font-family: 'Lato', sans-serif; /* Lato para el cuerpo del texto por su legibilidad */
            margin: 0;
            padding: 40px;
            color: var(--color-texto-oscuro);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            font-family: 'Montserrat', sans-serif; /* Montserrat para títulos por su fuerza */
            text-align: center;
            color: var(--color-acento-negro);
            font-size: 3.2em;
            font-weight: 800;
            margin-bottom: 60px;
            letter-spacing: -1px;
            text-transform: uppercase;
        }

        /* -------------------------------------- */
        /* --- 2. Barra de Navegación --- */
        /* -------------------------------------- */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            max-width: 1100px;
            margin-bottom: 50px;
            padding: 25px 40px;
            background: var(--color-fondo-intermedio); /* Gris claro de fondo para la navbar */
            border-radius: 15px;
            box-shadow: var(--sombra-suave);
            border: 1px solid var(--color-borde-gris);
            transition: all 0.3s ease-in-out;
        }
        .navbar:hover {
            box-shadow: var(--sombra-media);
        }

        .navbar .logo {
            font-family: 'Montserrat', sans-serif;
            color: var(--color-acento-negro);
            font-weight: 800;
            font-size: 2em;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .navbar .logo span {
            font-weight: 400;
            color: var(--color-texto-medio);
        }

        .navbar .links a {
            text-decoration: none;
            padding: 14px 25px;
            border-radius: 10px;
            font-weight: 600;
            margin-left: 20px;
            transition: all 0.2s ease-in-out;
            display: inline-flex; 
            align-items: center;
            gap: 10px;
            color: var(--color-texto-medio);
            background: none;
            border: 1px solid transparent;
        }
        .navbar .links a:hover {
            color: var(--color-acento-negro);
            background: var(--color-borde-gris);
            border-color: var(--color-borde-gris);
        }
        .navbar .links a.logout {
            background: var(--color-acento-negro); 
            color: white; 
            border-color: var(--color-acento-negro);
        }
        .navbar .links a.logout:hover {
            background: var(--color-acento-hover);
            border-color: var(--color-acento-hover);
        }

        /* -------------------------------------- */
        /* --- 3. Tarjeta de Formulario --- */
        /* -------------------------------------- */
        .card {
            width: 90%;
            max-width: 850px;
            margin: 30px auto;
            background: var(--color-fondo-claro); /* Blanco puro para la tarjeta */
            padding: 50px;
            border-radius: 20px;
            box-shadow: var(--sombra-media);
            border: 1px solid var(--color-borde-gris);
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card h2 {
            font-family: 'Montserrat', sans-serif;
            color: var(--color-acento-negro);
            font-size: 2.2em;
            margin-top: 0;
            margin-bottom: 40px;
            font-weight: 700;
            text-align: center;
            border-bottom: 2px solid var(--color-borde-gris);
            padding-bottom: 15px;
        }

        label {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: var(--color-texto-medio);
            display: block;
            margin-top: 25px;
            margin-bottom: 10px;
            font-size: 1em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid var(--color-borde-gris);
            background: var(--color-fondo-intermedio); /* Fondo gris claro para los inputs */
            color: var(--color-texto-oscuro);
            transition: all 0.3s ease-in-out;
            box-sizing: border-box;
            font-size: 1em;
        }
        
        input::placeholder, textarea::placeholder {
            color: var(--color-texto-medio);
            opacity: 0.7;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--color-acento-negro);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1); /* Sombra de foco negra sutil */
            background: var(--color-fondo-claro);
        }

        button, a.btn-action {
            background: var(--color-acento-negro);
            color: white;
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease-in-out;
            margin-top: 35px;
            margin-right: 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--sombra-media);
        }

        button:hover {
            background: var(--color-acento-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        /* Botón de Cancelar */
        .btn-cancelar {
            background: var(--color-borde-gris);
            color: var(--color-texto-oscuro);
            box-shadow: var(--sombra-suave);
        }
        .btn-cancelar:hover {
            background: #CCCCCC;
            color: var(--color-acento-negro);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        /* -------------------------------------- */
        /* --- 4. Tabla de Productos --- */
        /* -------------------------------------- */
        table {
            width: 90%;
            max-width: 1100px;
            margin-top: 60px;
            border-collapse: separate; 
            border-spacing: 0 15px; 
            background: none; 
        }

        thead th {
            background: none; 
            color: var(--color-texto-medio);
            padding: 20px;
            text-align: left;
            font-size: 1em;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--color-borde-gris);
        }
        
        tbody tr {
            background: var(--color-fondo-claro);
            border-radius: 15px; 
            transition: all 0.3s ease-in-out;
            box-shadow: var(--sombra-suave);
            border: 1px solid var(--color-borde-gris);
        }
        
        tbody tr:hover {
            transform: translateY(-3px); 
            box-shadow: var(--sombra-media);
        }

        td {
            padding: 20px;
            font-size: 1em;
            border: none; 
            color: var(--color-texto-oscuro);
        }
        
        tbody tr td:first-child { border-top-left-radius: 15px; border-bottom-left-radius: 15px; }
        tbody tr td:last-child { border-top-right-radius: 15px; border-bottom-right-radius: 15px; }

        a.btn {
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-right: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            transition: all 0.2s ease-in-out;
            background: var(--color-acento-negro);
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .editar {
            background: var(--color-texto-oscuro); /* Un gris muy oscuro para editar */
            color: white;
        }
        .editar:hover {
            background: var(--color-acento-hover);
        }
        .eliminar {
            background: var(--color-peligro);
            color: white;
        }
        .eliminar:hover {
            background: var(--color-peligro-hover);
        }

        /* Media queries para responsividad */
        @media (max-width: 800px) {
            body { padding: 20px; }
            h1 { font-size: 2.5em; margin-bottom: 40px; }
            .navbar { flex-direction: column; padding: 20px; border-radius: 10px; }
            .navbar .logo { font-size: 1.6em; margin-bottom: 15px; }
            .navbar .links { flex-direction: column; gap: 10px; }
            .navbar .links a { width: 100%; text-align: center; margin: 0; }
            .card { padding: 30px; border-radius: 15px; }
            .card h2 { font-size: 1.8em; margin-bottom: 30px; }
            table { margin-top: 40px; }
            thead { display: none; } 
            tbody, tr, td { display: block; width: 100%; }
            tr { 
                margin-bottom: 15px; 
                padding: 15px;
                background: var(--color-fondo-claro);
                border-radius: 10px;
                box-shadow: var(--sombra-suave);
            }
            td {
                border: none;
                position: relative;
                padding-left: 45%;
                text-align: right;
                font-size: 0.9em;
            }
            td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 40%;
                text-align: left;
                font-weight: 700;
                color: var(--color-acento-negro);
            }
            .acciones-col {
                 display: flex;
                 justify-content: flex-end;
                 gap: 8px;
                 padding-top: 15px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">VENTAS<span>CARS</span></div> 
    <div class="links">
        <a href="productos.php" class="inicio"><i class="fas fa-th-list"></i> Ver Listado</a>
        <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </div>
</div>

<h1>VENTASCARS ⋅˚₊‧ 𐙚 ‧₊˚ ⋅</h1>

<div class="card">
    <h2><?= $producto_editar ? '<i class="fas fa-edit"></i> Actualizar Vehículo' : '<i class="fas fa-plus"></i> Añadir Nuevo Modelo' ?></h2>
    <form method="POST" id="form-producto">
        <input type="hidden" name="id" id="id" value="<?= $producto_editar['id'] ?? '' ?>">

        <label for="nombre">Nombre del Modelo</label>
        <input type="text" name="nombre" id="nombre" value="<?= $producto_editar['nombre'] ?? '' ?>" placeholder="Ej: BMW M3 Competition" required>

        <label for="marca">Marca</label>
        <input type="text" name="marca" id="marca" value="<?= $producto_editar['marca'] ?? '' ?>" placeholder="Ej: BMW" required>

        <label for="precio">Precio (USD)</label>
        <input type="number" name="precio" id="precio" value="<?= $producto_editar['precio'] ?? '' ?>" placeholder="Ej: 85000" required>

        <label for="tipo_id">Tipo de Vehículo</label>
        <select name="tipo_id" id="tipo_id" required>
            <option value="">Seleccione el tipo...</option>
            <?php 
            // Necesitamos resetear el puntero si ya fue consumido arriba
            $tipos->data_seek(0); 
            while ($t = $tipos->fetch_assoc()) { 
                $selected = ($producto_editar['tipo_id'] ?? '') == $t['id'] ? 'selected' : '';
            ?>
                <option value="<?= $t['id'] ?>" <?= $selected ?>><?= htmlspecialchars($t['nombre']) ?></option>
            <?php } ?>
        </select>

        <label for="descripcion">Detalles y Especificaciones</label>
        <textarea name="descripcion" id="descripcion" rows="5" placeholder="Color, año, características del motor, extras de lujo..."><?= htmlspecialchars($producto_editar['descripcion'] ?? '') ?></textarea>

        <br>
        <button type="submit"><?= $producto_editar ? '<i class="fas fa-save"></i> Guardar Cambios' : '<i class="fas fa-upload"></i> Publicar Vehículo' ?></button>
        <?php if ($producto_editar): ?>
            <a class="btn-action btn-cancelar" href="dashboard.php"><i class="fas fa-times"></i> Descartar</a> 
        <?php endif; ?>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Modelo</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Tipo</th>
            <th>Detalles</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($p = $productos->fetch_assoc()) { ?>
        <tr>
            <td data-label="Modelo"><?= htmlspecialchars($p['nombre']) ?></td>
            <td data-label="Marca"><?= htmlspecialchars($p['marca']) ?></td>
            <td data-label="Precio">$<?= number_format($p['precio'], 0, '', '.') ?></td> 
            <td data-label="Tipo"><?= htmlspecialchars($p['tipo']) ?></td>
            <td data-label="Detalles"><?= htmlspecialchars($p['descripcion']) ?></td>

            <td data-label="Acciones" class="acciones-col">
                <a class="btn editar" href="dashboard.php?editar=<?= $p['id'] ?>"><i class="fas fa-pen"></i> Editar</a> 
                <a class="btn eliminar" href="dashboard.php?eliminar=<?= $p['id'] ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')"><i class="fas fa-trash-alt"></i> Eliminar</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php if ($producto_editar): ?>
<script>
    // Desplazamiento suave hacia el formulario al entrar en modo edición
    document.getElementById('form-producto').scrollIntoView({ behavior: 'smooth' });
</script>
<?php endif; ?>

</body>
</html>