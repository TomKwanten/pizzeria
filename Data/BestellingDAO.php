<?php
// Data/BestellingDAO.php
declare(strict_types=1);

namespace Data;

use \PDO;
use Entities\Bestelling;

class BestellingDAO {

    public function createBestelling(int $klantId, string $adres, float $totaalprijs, array $bestellijnen): Bestelling {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $dbh->beginTransaction();

        try {
            // Insert bestelling
            $stmt = $dbh->prepare("
                INSERT INTO bestelling (klantId, datum, adres, totaalprijs) 
                VALUES (:klantId, NOW(), :adres, :totaalprijs)
            ");
            $stmt->execute([
                ':klantId' => $klantId,
                ':adres' => $adres,
                ':totaalprijs' => $totaalprijs
            ]);
            $bestellingId = (int)$dbh->lastInsertId();

            // Insert bestellijnen
            $stmtLijn = $dbh->prepare("
                INSERT INTO bestellijn (bestellingId, productId, aantal, prijs) 
                VALUES (:bestellingId, :productId, :aantal, :prijs)
            ");
            foreach ($bestellijnen as $lijn) {
                $stmtLijn->execute([
                    ':bestellingId' => $bestellingId,
                    ':productId' => $lijn['productId'],
                    ':aantal' => $lijn['aantal'],
                    ':prijs' => $lijn['prijs']
                ]);
            }

            $dbh->commit();

            // Retourneer Bestelling zonder bestellijnen (altijd ophalen via getById)
            return new Bestelling(
                $bestellingId,
                $klantId,
                '', // later ophalen via getById
                $adres,
                $totaalprijs,
                []
            );
        } catch (\Exception $e) {
            $dbh->rollBack();
            throw $e;
        }
    }

    public function getById(int $id): ?Bestelling {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        $stmt = $dbh->prepare("SELECT * FROM bestelling WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        // Haal bestellijnen op via aparte DAO
        $bestellijnDAO = new BestellijnDAO();
        $bestellijnen = $bestellijnDAO->getByBestellingId((int)$row["id"]);

        return new Bestelling(
            (int)$row["id"],
            (int)$row["klantId"],
            $row["datum"],
            $row["adres"],
            (float)$row["totaalprijs"],
            $bestellijnen
        );
    }
}
