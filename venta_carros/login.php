<?php
session_start();
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $stmt = $conn->prepare("SELECT id, clave FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hash);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($clave, $hash)) {
        $_SESSION['user'] = $usuario;
        $_SESSION['id'] = $id;
        header("Location: dashboard.php"); // Redirige al dashboard principal
        exit;
    } else {
        $error = "Usuario o clave incorrecta";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar Sesión - Monocromático</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Lato:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
/* -------------------------------------- */
/* --- 1. Variables Monocromáticas --- */
/* -------------------------------------- */
:root {
    --color-fondo-claro: #FFFFFF;     /* Blanco puro para la tarjeta */
    --color-fondo-oscuro: #0A0A0A;    /* Negro casi puro para el fondo del body */
    --color-fondo-intermedio: #F5F5F5; /* Gris muy claro para inputs y hover */
    --color-acento-negro: #000000;    /* Negro puro para botones principales y títulos */
    --color-acento-hover: #333333;    /* Gris muy oscuro para hover de acento */
    --color-texto-principal: #212121; /* Texto oscuro */
    --color-texto-secundario: #616161; /* Gris oscuro para placeholders */
    --color-borde-gris: #E0E0E0;      /* Gris claro para bordes */
    --color-peligro: #D32F2F;         /* Rojo de error */
    --sombra-fuerte: 0 15px 45px rgba(0, 0, 0, 0.7);
}

body {
    font-family: 'Lato', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: var(--color-fondo-oscuro); /* Fondo negro */
    color: var(--color-texto-principal);
    margin: 0;
}

/* -------------------------------------- */
/* --- 2. Contenedor de Login (Tarjeta) --- */
/* -------------------------------------- */
.login-container {
    background: var(--color-fondo-claro); /* Tarjeta blanca */ 
    padding: 40px; 
    border-radius: 20px; 
    box-shadow: var(--sombra-fuerte); /* Sombra intensa en fondo negro */
    width: 380px;
    text-align: center;
    border: 1px solid var(--color-borde-gris);
}
.login-container h2 {
    font-family: 'Montserrat', sans-serif;
    color: var(--color-acento-negro); /* Título en negro */
    margin-bottom: 30px;
    letter-spacing: 0.5px;
    font-size: 2.2em;
    font-weight: 800;
}

/* -------------------------------------- */
/* --- 3. Inputs y Errores --- */
/* -------------------------------------- */
.login-container input {
    width: 100%;
    padding: 15px;
    margin: 15px 0;
    border-radius: 10px;
    border: 1px solid var(--color-borde-gris); 
    background: var(--color-fondo-intermedio); /* Fondo gris claro en input */
    color: var(--color-texto-principal); 
    font-size: 1em;
    box-sizing: border-box;
    transition: all 0.3s;
}
.login-container input::placeholder {
    color: var(--color-texto-secundario);
    opacity: 0.8;
}
.login-container input:focus {
    border-color: var(--color-acento-negro); /* Borde que se ilumina en negro */
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    background: var(--color-fondo-claro);
    outline: none;
}

.error {
    color: var(--color-peligro);
    background: var(--color-fondo-intermedio); /* Fondo gris suave para el error */
    border: 1px solid var(--color-peligro);
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 0.9em;
}

/* -------------------------------------- */
/* --- 4. Botón --- */
/* -------------------------------------- */
.login-container button {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 10px;
    background: var(--color-acento-negro); /* Botón en negro puro */
    color: white;
    font-size: 1.1em;
    font-weight: 700;
    cursor: pointer;
    margin-top: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    transition: 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}
.login-container button:hover {
    background: var(--color-acento-hover);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5); 
}
</style>
</head>
<body>

<div class="login-container">
    <h2><i class="fas fa-lock"></i> ADMINISTRACIÓN</h2>

    <?php if(!empty($error)): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>
    </form>
</div>

</body>
</html>