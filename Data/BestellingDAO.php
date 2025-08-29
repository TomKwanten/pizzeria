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
            $stmt = $dbh->prepare("INSERT INTO bestelling (klantId, datum, adres, totaalprijs) VALUES (:klantId, NOW(), :adres, :totaalprijs)");
            $stmt->execute([
                ':klantId' => $klantId,
                ':adres' => $adres,
                ':totaalprijs' => $totaalprijs
            ]);
            $bestellingId = (int)$dbh->lastInsertId();

            // Insert bestellijnen
            $stmtLijn = $dbh->prepare("INSERT INTO bestellijn (bestellingId, productId, aantal, prijs) VALUES (:bestellingId, :productId, :aantal, :prijs)");
            foreach ($bestellijnen as $lijn) {
                $stmtLijn->execute([
                    ':bestellingId' => $bestellingId,
                    ':productId' => $lijn['productId'],
                    ':aantal' => $lijn['aantal'],
                    ':prijs' => $lijn['prijs']
                ]);
            }

            $dbh->commit();
            $dbh = null;

            // Maak Bestelling-object aan
            return new Bestelling(
                $bestellingId,
                $klantId,
                date('Y-m-d H:i:s'),
                $adres,
                $totaalprijs,
                $bestellijnen
            );
        } catch (\Exception $e) {
            $dbh->rollBack();
            $dbh = null;
            throw $e;
        }
    }

    public function getById(int $id): ?Bestelling {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        // Haal de bestelling op
        $stmt = $dbh->prepare("SELECT * FROM bestelling WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $dbh = null;
            return null;
        }

        // Haal de bestellijnen op met productnaam
        $stmtLijnen = $dbh->prepare("
            SELECT bl.productId, bl.aantal, bl.prijs, p.naam AS productNaam
            FROM bestellijn bl
            LEFT JOIN product p ON bl.productId = p.id
            WHERE bl.bestellingId = :bestellingId
        ");
        $stmtLijnen->execute([":bestellingId" => $id]);
        $lijnenData = $stmtLijnen->fetchAll(PDO::FETCH_ASSOC);

        $dbh = null;

        $bestellijnen = [];
        foreach ($lijnenData as $lijn) {
            $bestellijnen[] = [
                "productId" => (int)$lijn["productId"],
                "aantal" => (int)$lijn["aantal"],
                "prijs" => (float)$lijn["prijs"],
                "productNaam" => $lijn["productNaam"] ?? 'Onbekend'
            ];
        }

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
