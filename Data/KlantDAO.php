<?php
// Data/KlantDAO.php
declare(strict_types=1);

namespace Data;

use Entities\Klant;
use Exceptions\GebruikerBestaatNietException;
use Exceptions\GebruikerBestaatAlException;
use Exceptions\WachtwoordIncorrectException;
use PDO;

class KlantDAO {
    public function login(string $email, string $wachtwoord): Klant {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT * FROM klant WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        if (!$row) {
            throw new GebruikerBestaatNietException();
        }

        if (!password_verify($wachtwoord, $row["wachtwoord"])) {
            throw new WachtwoordIncorrectException();
        }

        $klant = new Klant((int)$row["id"]);
        $klant->setVoornaam($row["voornaam"]);
        $klant->setNaam($row["naam"]);
        $klant->setStraat($row["straat"]);
        $klant->setHuisnummer($row["huisnummer"]);
        $klant->setPostcode($row["postcode"]);
        $klant->setGemeente($row["gemeente"]);
        $klant->setTelefoon($row["telefoon"]);
        $klant->setEmail($row["email"]);
        // wachtwoord niet teruggeven
        $klant->setPromotie((bool)$row["promotie"]);
        $klant->setOpmerkingen($row["opmerkingen"]);

        return $klant;
    }

    public function register(Klant $klant): Klant {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        // check of email al bestaat
        $stmtCheck = $dbh->prepare("SELECT id FROM klant WHERE email = :email");
        $stmtCheck->bindValue(":email", $klant->getEmail());
        $stmtCheck->execute();
        if ($stmtCheck->rowCount() > 0) {
            throw new GebruikerBestaatAlException();
        }

        $stmt = $dbh->prepare("
            INSERT INTO klant (voornaam, naam, straat, huisnummer, postcode, gemeente, telefoon, email, wachtwoord)
            VALUES (:voornaam, :naam, :straat, :huisnummer, :postcode, :gemeente, :telefoon, :email, :wachtwoord)
        ");

        $stmt->bindValue(":voornaam", $klant->getVoornaam());
        $stmt->bindValue(":naam", $klant->getNaam());
        $stmt->bindValue(":straat", $klant->getStraat());
        $stmt->bindValue(":huisnummer", $klant->getHuisnummer());
        $stmt->bindValue(":postcode", $klant->getPostcode());
        $stmt->bindValue(":gemeente", $klant->getGemeente());
        $stmt->bindValue(":telefoon", $klant->getTelefoon());
        $stmt->bindValue(":email", $klant->getEmail());
        $stmt->bindValue(":wachtwoord", password_hash($klant->getWachtwoord(), PASSWORD_DEFAULT));

        $stmt->execute();
        $klantId = (int)$dbh->lastInsertId();
        $dbh = null;

        return new Klant($klantId);
    }
}
