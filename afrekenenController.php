<?php
// afrekenenController.php
declare(strict_types=1);

require_once("init.php");
require_once("initSession.php");
require_once("initTwig.php");

use Business\KlantService;
use Business\ProductService;
use Entities\Klant;
use Exceptions\GebruikerBestaatNietException;
use Exceptions\GebruikerBestaatAlException;
use Exceptions\WachtwoordIncorrectException;
use Exceptions\OngeldigEmailadresException;
use Exceptions\WachtwoordenKomenNietOvereenException;

$klantSvc = new KlantService();
$productSvc = new ProductService();

$foutmelding = '';
$winkelmandje = [];
$totaalPrijs = 0.0;
// Leverbare postcodes
$leverbarePostcodes = ['3600', '3665', '3690', '3660', '3590', '3630', '3650', '3530', '3670']; 

// Winkelmandje samenstellen
$winkelmandjeSession = $_SESSION['winkelmandje'] ?? [];
foreach ($winkelmandjeSession as $id => $aantal) {
    $product = $productSvc->getProductById($id);
    if ($product) {
        $prijs = $product->getPromotieprijs() ?? $product->getPrijs();
        $totaalPrijs += $prijs * $aantal;
        $winkelmandje[] = [
            'product' => $product,
            'aantal' => $aantal,
            'prijs' => $prijs
        ];
    }
}

// Check of gebruiker ingelogd is
$ingelogd = $_SESSION['klant'] ?? false;

// Haal laatste gebruikte e-mail uit cookie
$laatsteEmail = $_COOKIE['laatste_email'] ?? '';

// POST-verwerking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie = $_POST['actie'] ?? '';

    if ($actie === 'login') {
        $foutmelding = loginKlant($klantSvc, $_POST['email'], $_POST['wachtwoord']);
    } elseif ($actie === 'registratie') {
        $foutmelding = registreerKlant($klantSvc, $_POST);
    } elseif ($actie === 'logout') {
        unset($_SESSION['klant']);
        $ingelogd = false;
    } elseif ($actie === 'bevestig') {
        // Controleer of de postcode leverbaar is
        $postcode = $_POST['postcode'] ?? '';
        if (!in_array($postcode, $leverbarePostcodes)) {
            $foutmelding = "Sorry, we leveren niet in deze gemeente/postcode.";
        } else {
            // Geen fout, doorgaan naar bestelling bevestigen
            $_SESSION['adres'] = [
                'straat' => $_POST['straat'],
                'huisnummer' => $_POST['huisnummer'],
                'postcode' => $_POST['postcode'],
                'gemeente' => $_POST['gemeente']
            ];
            header("Location: bestellingBevestigenController.php");
            exit;
        }
    }

    // Update ingelogd status
    $ingelogd = $_SESSION['klant'] ?? false;
}

// Render Twig
print $twig->render("afrekenen.twig", [
    'winkelmandje' => $winkelmandje,
    'totaalPrijs' => $totaalPrijs,
    'ingelogd' => $ingelogd,
    'foutmelding' => $foutmelding,
    'laatsteEmail' => $laatsteEmail,
]);

// Functies
function loginKlant(KlantService $svc, string $email, string $wachtwoord): string {
    try {
        $klant = $svc->login($email, $wachtwoord);
        $_SESSION['klant'] = $klant;

        // Cookie instellen voor laatste e-mail, 30 dagen geldig
        setcookie('laatste_email', $email, time() + (30*24*60*60), "/");

        return '';
    } catch (GebruikerBestaatNietException) {
        return "Deze gegevens zijn nog niet bekend. Registreer eerst een account.";
    } catch (WachtwoordIncorrectException) {
        return "Het opgegeven wachtwoord is niet correct.";
    } catch (\Exception $e) {
        return "Er is een onverwachte fout opgetreden: " . $e->getMessage();
    }
}

function registreerKlant(KlantService $svc, array $postData): string {
    try {
        $klant = new Klant();
        $klant->setVoornaam($postData['voornaam']);
        $klant->setNaam($postData['naam']);
        $klant->setStraat($postData['straat']);
        $klant->setHuisnummer($postData['huisnummer']);
        $klant->setPostcode($postData['postcode']);
        $klant->setGemeente($postData['gemeente']);
        $klant->setTelefoon($postData['telefoon'] ?? null);
        $klant->setEmail($postData['email']);
        $klant->setWachtwoord($postData['wachtwoord'], $postData['wachtwoord']);

        $klant = $svc->registreer($klant);
        $_SESSION['klant'] = $klant;
        return '';
    } catch (GebruikerBestaatAlException) {
        return "Er bestaat al een gebruiker met dit e-mailadres.";
    } catch (OngeldigEmailadresException) {
        return "Het opgegeven e-mailadres is ongeldig.";
    } catch (WachtwoordenKomenNietOvereenException) {
        return "De opgegeven wachtwoorden komen niet overeen.";
    } catch (\Exception $e) {
        return "Er is een onverwachte fout opgetreden: " . $e->getMessage();
    }
}
