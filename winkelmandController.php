<?php
// winkelmandController.php
declare(strict_types=1);

spl_autoload_register();
require_once("initTwig.php");
require_once("initSession.php");

use Business\ProductService;

$productSvc = new ProductService();

// Actie ophalen uit POST
$actie = $_POST['actie'] ?? null;
$productId = isset($_POST['id']) ? (int)$_POST['id'] : null;

if ($actie && $productId) {
    switch ($actie) {
        case 'toevoegen':
            if (isset($_SESSION['winkelmandje'][$productId])) {
                $_SESSION['winkelmandje'][$productId]++;
            } else {
                $_SESSION['winkelmandje'][$productId] = 1;
            }
            break;
        case 'verwijderen':
            if (isset($_SESSION['winkelmandje'][$productId])) {
                unset($_SESSION['winkelmandje'][$productId]);
            }
            break;
    }
}

// Productenlijst ophalen
$producten = $productSvc->getAllProducts();

// Winkelmandje-items samenstellen
$mandje = [];
foreach ($_SESSION['winkelmandje'] as $id => $aantal) {
    $product = $productSvc->getProductById($id);
    if ($product) {
        $mandje[] = ['product' => $product, 'aantal' => $aantal];
    }
}

// Twig renderen
print $twig->render("winkelmand.twig", [
    'productenLijst' => $producten,
    'winkelmandje' => $mandje
]);
