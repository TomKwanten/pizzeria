<?php
// Data/ProductDAO.php
declare(strict_types=1);

namespace Data;

use \PDO;
use Entities\Product;

class ProductDAO {

    public function getAll(): array {
        $sql = "SELECT * FROM product WHERE beschikbaar = 1";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        
        $lijst = array();
        foreach ($resultSet as $rij) {
            $product = new Product(
                (int)$rij["id"],
                $rij["naam"],
                (float)$rij["prijs"],
                $rij["promotieprijs"] !== null ? (float)$rij["promotieprijs"] : null,
                $rij["beschrijving"] ?? null,
                (bool)$rij["beschikbaar"]
            );
            array_push($lijst, $product);
        }
        $dbh = null;
        return $lijst;
    }

    public function getById(int $id): ?Product {
        $sql = "SELECT * FROM product WHERE id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute([":id" => $id]);
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$rij) {
            return null;
        }

        $product = new Product(
            (int)$rij["id"],
            $rij["naam"],
            (float)$rij["prijs"],
            $rij["promotieprijs"] !== null ? (float)$rij["promotieprijs"] : null,
            $rij["beschrijving"] ?? null,
            (bool)$rij["beschikbaar"]
        );
        $dbh = null;
        return $product;
    }
}
