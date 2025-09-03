<?php
// Entities/Bestellijn.php
declare(strict_types=1);

namespace Entities;

class Bestellijn {
    private int $id;
    private int $bestellingId;
    private int $productId;
    private int $aantal;
    private float $prijs;
    private ?string $productNaam;

    public function __construct(int $id, int $bestellingId, int $productId, int $aantal, float $prijs, ?string $productNaam = null) {
        $this->id = $id;
        $this->bestellingId = $bestellingId;
        $this->productId = $productId;
        $this->aantal = $aantal;
        $this->prijs = $prijs;
        $this->productNaam = $productNaam;
    }

    public function getId(): int { return $this->id; }
    public function getBestellingId(): int { return $this->bestellingId; }
    public function getProductId(): int { return $this->productId; }
    public function getAantal(): int { return $this->aantal; }
    public function getPrijs(): float { return $this->prijs; }
    public function getProductNaam(): ?string { return $this->productNaam; }
}
