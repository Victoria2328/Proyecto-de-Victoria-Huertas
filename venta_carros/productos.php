<?php
session_start();
require_once __DIR__ . '/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Productos - Venta de Carros</title>
  <link rel="stylesheet" href="css/style.css"/> 
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="manhwa.png">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #fdfaf6;
    }
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #222;
      padding: 20px 30px;
      color: #fff;
    }
    .navbar .logo {
      font-size: 1.5em;
      font-weight: bold;
    }
    .navbar .links a {
      color: #fff;
      text-decoration: none;
      margin-left: 20px;
    }
    .navbar .links a:hover {
      text-decoration: underline;
    }
    main {
      padding: 30px;
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    .producto {
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .producto img {
      max-width: 100%;
      border-radius: 6px;
      margin-bottom: 10px;
    }
    
    /* CAMBIOS PARA EL COLOR NEGRO: MODELO Y MARCA */
    .producto h3, .producto h4 {
      margin: 5px 0;
      color: #000; /* Color negro aplicado */
    }
    
    /* CAMBIOS PARA EL COLOR NEGRO: PRECIO */
    .producto p {
      color: #000; /* Color negro aplicado (era #333) */
      font-weight: 500;
      margin-bottom: 10px;
    }
    
    .producto button {
      padding: 8px 12px;
      background-color: #6a5f8f;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }
    .producto button:hover {
      background-color:#776b99;
    }
    aside#carrito {
      margin-top: 50px;
      background: #0d0d0d;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    aside#carrito ul {
      list-style: none;
      padding: 0;
    }
    aside#carrito li {
      margin-bottom: 5px;
    }
  </style>
</head>
<body>
  

        
        
</style>
<body>
  <nav class="navbar">
  <div class="logo">VENTA DE CARROS ༘˚⋆𐙚｡⋆𖦹.✧˚</div>
  <div class="links">
    <a href="dashboard.php">Dashboard 🧸ྀི</a>
    <a href="tipos.php">Tipos𖹭</a>
    <a href="logout.php">Cerrar sesión🔒</a>
  </div>

</nav>
<main>
    <article class="producto">
      <img src="deportivos/BMW DEPORTIVO.png" alt="Portada Carro 1" />
      <h3> MODELO: M3 COMPETITION PRO </h3>
      <h4> MARCA: BMW</h4>
      <p> PRECIO: $680.000.000 </p>
      <button onclick="agregarAlCarrito('BMW M3 COMPETITION PRO', 680000000)">Agregar al carrito</button>
    </article>


    <article class="producto">
      <img src="electronico/BYD Seagull.png" alt="VEHICULO 2" />
      <h3> Modelo:Seagull </h3>
      <h4> marca: BYD</h4>
      <p>Precio: $76.990.000 y  $ 82.990.000 COP </p>
      <button onclick="agregarAlCarrito('BYD Seagull', 76990000)">Agregar al carrito</button>
    </article>



    <article class="producto">
      <img src="gasolina/Toyota CCorolla XLi 1.6.jpg" alt="VEHICULO 3" />
      <h3> Modelo :Corolla XLi 1.6</h3>
      <h4> marca: TOYOTA</h4>
      <p>Precio: $1.950.000.000 COP</p>
      <button onclick="agregarAlCarrito('Toyota Corolla XLi 1.6', 95000000)">Agregar al carrito</button>
    </article>


      
    <article class="producto">
      <img src="camionetas/MG ZS.jpg" alt="VEHICULO 4" />
      <h3> Modelo :MG</h3>
      <h4> marca:ZS </h4>
      <p>Precio: $74.990.000 COP</p>
      <button onclick="agregarAlCarrito('MG ZS', 74990000)">Agregar al carrito</button>
    </article>




    
    <article class="producto">
      <img src="deportivos/Maserati MC20.jpg" alt="VEHICULO 5" />
      <h3> Modelo :MC20</h3>
      <h4> marca:Maserati </h4>
      <p>Precio: $1.168.000.000 COP</p>
      <button onclick="agregarAlCarrito('Maserati MC20', 1168000000)">Agregar al carrito</button>
    </article>



<article class="producto">
      <img src="deportivos/gts.jpg" alt="VEHICULO 5" />
      <h3> Modelo :992 C4 GTS</h3>
      <h4> marca:Porsche</h4>
      <p>Precio: $1.275.920.000 COP</p>
      <button onclick="agregarAlCarrito('Porsche 992 C4 GTS', 1275920000)">Agregar al carrito</button>
    </article>


    

<article class="producto">
      <img src="camionetas/camioneta.jpg" alt="VEHICULO 5" />
      <h3> Modelo :Ranger XL 4×4</h3>
      <h4> marca:Ford</h4>
      <p>Precio: $195.990.000 COP</p>
      <button onclick="agregarAlCarrito('Ford Ranger XL 4×4', 195990000)">Agregar al carrito</button>
    </article>



<article class="producto">
      <img src="deportivos/urus.jpg" alt="VEHICULO 5" />
      <h3> Modelo :Urus</h3>
      <h4> marca:Lamborghini</h4>
      <p>Precio: $2.470.990.000 COP</p>
      <button onclick="agregarAlCarrito('Lamborghini Urus', 2470990000)">Agregar al carrito</button>
    </article>


    <article class="producto">
      <img src="electronico/Tesla.jpg" alt="VEHICULO 5" />
      <h3> Modelo :Model 3 Performance AWD</h3>
      <h4> marca:Tesla</h4>
      <p>Precio: $164,990,000 COP</p>
     <button onclick="agregarAlCarrito('Tesla Model 3 Performance AWD', 164990000)">Agregar al carrito</button>
    </article>

   <article class="producto">
      <img src="camionetas/Ford.jpg" alt="VEHICULO 5" />
      <h3> Modelo :Maverick híbrida (XLT)</h3>
      <h4> marca:Ford</h4>
      <p>Precio: $149,990,000 COP</p>
      <button onclick="agregarAlCarrito('Ford Maverick híbrida (XLT)', 149990000)">Agregar al carrito</button>
    </article>

    

    <article class="producto">
      <img src="gasolina/Renult.jpg"alt="VEHICULO 5" />
      <h3> Modelo :Koleos Intens 4x4</h3>
      <h4> marca:Renault</h4>
      <p>Precio: $159.340.000 COP</p>
      <button onclick="agregarAlCarrito('Renault Koleos Intens 4x4', 159340000)">Agregar al carrito</button>
    </article>

    

    <aside id="carrito" aria-label="Carrito de compras">
    <h2>Carrito 🛒</h2>
    <ul id="lista-carrito">
      <li>No hay productos en el carrito</li>
    </ul> 
    <button onclick="finalizarCompra()">Finalizar compra</button>
  </aside>
</main>
 <script>
  const carrito = [];
  const listaCarrito = document.getElementById('lista-carrito');

  function actualizarCarrito() {
    listaCarrito.innerHTML = '';
    if (carrito.length === 0) {
      listaCarrito.innerHTML = '<li>No hay productos en el carrito</li>';
      return;
    }
    carrito.forEach((item, index) => {
      const li = document.createElement('li');
      li.textContent = `${item.nombre} - $${new Intl.NumberFormat('es-CO').format(item.precio)}`;

      const btnEliminar = document.createElement('button');
      btnEliminar.textContent = 'X';
      btnEliminar.onclick = () => {
        carrito.splice(index, 1);
        actualizarCarrito();
      };
      li.appendChild(btnEliminar);
      listaCarrito.appendChild(li);
    });
  }

  function agregarAlCarrito(nombre, precio) {
    carrito.push({ nombre, precio });
    actualizarCarrito();
  }

  function finalizarCompra() {
    if (carrito.length === 0) {
      alert('El carrito está vacío.');
      return;
    }
    let total = carrito.reduce((sum, item) => sum + item.precio, 0);
    alert(`Gracias por tu compra. Total a pagar: $${new Intl.NumberFormat('es-CO').format(total)}`);
    carrito.length = 0;
    actualizarCarrito();
  }
</script>
</body>
</html>
