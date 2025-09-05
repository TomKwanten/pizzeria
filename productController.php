<?php
// productController.php
declare(strict_types=1);

// **Belangrijk:** eerst autoloader laden, zodat PHP classes kan vinden bij unserialize
require_once("init.php");

// Dan session starten
require_once("initSession.php"); 

// Daarna Twig initialiseren
require_once("initTwig.php");

// Toon altijd alle fouten
error_reporting(E_ALL);
ini_set('display_errors', '1');

use Business\ProductService;

try {
    $productSvc = new ProductService();
} catch (Exception $e) {
    echo "Fout bij ProductService aanmaken: " . $e->getMessage();
    exit;
}

try {
    $productenLijst = $productSvc->getAllProducts();
    $count = is_array($productenLijst) ? count($productenLijst) : 0;
    // Debug: aantal producten ophalen
    // echo "Aantal producten: $count";
} catch (Exception $e) {
    echo "Fout bij getAllProducts: " . $e->getMessage();
    exit;
}

// Winkelmandje-items samenstellen
$winkelmandje = [];
if (isset($_SESSION['winkelmandje']) && is_array($_SESSION['winkelmandje'])) {
    foreach ($_SESSION['winkelmandje'] as $id => $aantal) {
        try {
            $product = $productSvc->getProductById((int)$id);
            if ($product) {
                $winkelmandje[] = ['product' => $product, 'aantal' => $aantal];
            }
        } catch (Exception $e) {
            continue; // ga door met andere producten
        }
    }
}

// Ingelogd klant
$ingelogd = $_SESSION['klant'] ?? null;

// Twig render
try {
    print $twig->render("productlijst.twig", [
        "productenLijst" => $productenLijst,
        "winkelmandje" => $winkelmandje,
        "ingelogd" => $ingelogd
    ]);
} catch (Exception $e) {
    echo "Fout bij Twig render: " . $e->getMessage();
    exit;
}
