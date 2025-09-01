<?php
// productController.php
declare(strict_types=1);

require_once("init.php");
require_once("initTwig.php");
require_once("initSession.php"); // om session te starten

use Business\ProductService;

$productSvc = new ProductService();
$productenLijst = $productSvc->getAllProducts();

// Winkelmandje-items samenstellen (zelfde structuur als in winkelmandController)
$winkelmandje = [];
foreach ($_SESSION['winkelmandje'] ?? [] as $id => $aantal) {
    $product = $productSvc->getProductById((int)$id);
    if ($product) {
        $winkelmandje[] = ['product' => $product, 'aantal' => $aantal];
    }
}

// Als je ingelogd bent
$ingelogd = $_SESSION['klant'] ?? null;

print $twig->render("productlijst.twig", [
    "productenLijst" => $productenLijst,
    "winkelmandje" => $winkelmandje,
    "ingelogd" => $ingelogd
]);
