
<?php
//requerimos el controlador de las rutas
require_once "controllers/routes.controllers.php";
require_once "controllers/cursos.controller.php";
require_once "controllers/clientes.controller.php";
require_once "models/cursos.model.php";
require_once "models/cliente.modelo.php";

//creamos una instancia de la clase del controlador
$routes = new RoutesController();

//intaciamos el metodo que contiene la clase del controlador
$routes->inicio();




?>