<?php
// bestellingBevestigenController.php
declare(strict_types=1);

require_once("init.php");
require_once("initTwig.php");
require_once("initSession.php");

use Business\ProductService;
use Business\BestellingService;

// Check of gebruiker ingelogd is
$klant = $_SESSION['klant'] ?? null;
if (!$klant) {
    header("Location: afrekenenController.php");
    exit;
}

// Winkelmandje ophalen
$winkelmandje = $_SESSION['winkelmandje'] ?? [];
if (empty($winkelmandje)) {
    header("Location: index.php");
    exit;
}

// Adresgegevens uit POST
$straat = $_POST['straat'] ?? '';
$huisnummer = $_POST['huisnummer'] ?? '';
$postcode = $_POST['postcode'] ?? '';
$gemeente = $_POST['gemeente'] ?? '';
$adres = "$straat $huisnummer, $postcode $gemeente";

// Bestellijnen voorbereiden
$productSvc = new ProductService();
$bestellijnen = [];
foreach ($winkelmandje as $id => $aantal) {
    $product = $productSvc->getProductById((int)$id);
    if ($product) {
        $prijs = $product->getPromotieprijs() ?? $product->getPrijs();
        $bestellijnen[] = [
            "productId" => (int)$id,
            "aantal" => $aantal,
            "prijs" => $prijs
        ];
    }
}

// Bestelling plaatsen via service
$bestellingSvc = new BestellingService();
$bestelling = $bestellingSvc->plaatsBestelling($klant->getId(), $adres, $bestellijnen);

// Winkelmandje leegmaken
unset($_SESSION['winkelmandje']);

// Render Twig
print $twig->render("bestellingBevestigd.twig", [
    "bestelling" => $bestelling
]);
