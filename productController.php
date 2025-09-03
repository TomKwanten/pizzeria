<?php
// productController.php
declare(strict_types=1);

// Start session zo vroeg mogelijk
require_once("initSession.php"); // om session te starten

// Toon altijd alle fouten
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Autoload en Twig
require_once("init.php");
require_once("initTwig.php");

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
