<?php
// Entities/Bestelling.php
declare(strict_types=1);

namespace Entities;

class Bestelling {
    private int $id;
    private int $klantId;
    private string $datum;
    private string $adres;
    private float $totaalprijs;
    private array $bestellijnen = []; // bestellijnen toevoegen

    public function __construct(
        int $id,
        int $klantId,
        string $datum,
        string $adres,
        float $totaalprijs,
        array $bestellijnen = []
    ) {
        $this->id = $id;
        $this->klantId = $klantId;
        $this->datum = $datum;
        $this->adres = $adres;
        $this->totaalprijs = $totaalprijs;
        $this->bestellijnen = $bestellijnen;
    }

    // Getters en setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getKlantId(): int { return $this->klantId; }
    public function setKlantId(int $klantId): void { $this->klantId = $klantId; }

    public function getDatum(): string { return $this->datum; }
    public function setDatum(string $datum): void { $this->datum = $datum; }

    public function getAdres(): string { return $this->adres; }
    public function setAdres(string $adres): void { $this->adres = $adres; }

    public function getTotaalprijs(): float { return $this->totaalprijs; }
    public function setTotaalprijs(float $totaalprijs): void { $this->totaalprijs = $totaalprijs; }

    public function getBestellijnen(): array { return $this->bestellijnen; }
    public function setBestellijnen(array $bestellijnen): void { $this->bestellijnen = $bestellijnen; }
}
