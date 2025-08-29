<?php
// productController.php
declare(strict_types=1);

require_once("init.php");
require_once("initTwig.php"); // Twig initialiseren

use Business\ProductService;

$productSvc = new ProductService();
$productenLijst = $productSvc->getAllProducts();

print $twig->render("productlijst.twig", ["productenLijst" => $productenLijst]);

