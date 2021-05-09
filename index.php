<?php
ob_start();

require __DIR__."/vendor/autoload.php";

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(ROOT);
$route->namespace("Source\App");

/**
 *WEB ROUTES
 */

$route->group(null);
$route->get("/", "Web:home");
$route->get("/produto/{id}", "Web:product");
$route->post("/produto/{id}", "Web:appTicket");
$route->get("/login", "Web:login");
$route->post("/login", "Web:login");


/**
 *ADMIN ROUTES
 */

$route->group("admin");

$route->get("/dash", "Admin:adminArea");
$route->post("/cupom", "Admin:ticket");

$route->get("/cupom/editar/{id}", "Admin:ticketEdit");
$route->post("/cupom/editar/{id}", "Admin:ticketUpdate");
$route->get("/cupom/excluir/{id}", "Admin:ticketDelete");

$route->get("/produto", "Admin:adminProduct");
$route->post("/produto/{post}", "Admin:adminProduct");

$route->get("/produto/editar/{id}", "Admin:productEdit");
$route->post("/produto/editar/{id}/{post}", "Admin:productUpdate");

$route->get("/produto/excluir/{id}", "Admin:productDelete");

$route->get("/produto/excluir/{id}", "Admin:productDelete");
$route->get("/sair", "Admin:exit");


/**
 *ERROR ROUTES
 */
$route->group("ops");
$route->get("/{errcode}", "Web:error");

/**
 *PROCESS ROUTES
 */
$route->dispatch();

if ($route->error()){
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();
