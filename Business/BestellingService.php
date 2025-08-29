<?php
// Business/BestellingService.php
declare(strict_types=1);

namespace Business;

use Data\BestellingDAO;
use Entities\Bestelling;

class BestellingService {
    private BestellingDAO $bestellingDAO;

    public function __construct() {
        $this->bestellingDAO = new BestellingDAO();
    }

    /**
     * Plaats een bestelling
     *
     * @param int $klantId
     * @param string $adres
     * @param array $winkelmandje Array van ['productId' => int, 'aantal' => int, 'prijs' => float]
     * @return Bestelling
     */
    public function plaatsBestelling(int $klantId, string $adres, array $winkelmandje): Bestelling {
        $totaalprijs = 0.0;
        foreach ($winkelmandje as $item) {
            $totaalprijs += $item['prijs'] * $item['aantal'];
        }

        return $this->bestellingDAO->createBestelling($klantId, $adres, $totaalprijs, $winkelmandje);
    }

    public function haalBestellingOp(int $id): ?Bestelling {
        return $this->bestellingDAO->getById($id);
    }
}
