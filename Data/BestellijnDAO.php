<?php
// Data/BestellijnDAO.php
declare(strict_types=1);

namespace Data;

use \PDO;
use Entities\Bestellijn;

class BestellijnDAO {
    public function getByBestellingId(int $bestellingId): array {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        $stmt = $dbh->prepare("
            SELECT bl.id, bl.bestellingId, bl.productId, bl.aantal, bl.prijs, p.naam AS productNaam
            FROM bestellijn bl
            LEFT JOIN product p ON bl.productId = p.id
            WHERE bl.bestellingId = :bestellingId
        ");
        $stmt->execute([":bestellingId" => $bestellingId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dbh = null;

        $bestellijnen = [];
        foreach ($rows as $row) {
            $bestellijnen[] = new Bestellijn(
                (int)$row["id"],
                (int)$row["bestellingId"],
                (int)$row["productId"],
                (int)$row["aantal"],
                (float)$row["prijs"],
                $row["productNaam"] ?? null
            );
        }

        return $bestellijnen;
    }
}
