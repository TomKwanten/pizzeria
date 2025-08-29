<?php
// Entities/Product.php
declare(strict_types=1);

namespace Entities;

class Product {
    private int $id;
    private string $naam;
    private float $prijs;
    private ?float $promotieprijs;
    private ?string $beschrijving;
    private bool $beschikbaar;

    public function __construct(int $id, string $naam, float $prijs, ?float $promotieprijs, ?string $beschrijving, bool $beschikbaar) {
        $this->id = $id;
        $this->naam = $naam;
        $this->prijs = $prijs;
        $this->promotieprijs = $promotieprijs;
        $this->beschrijving = $beschrijving;
        $this->beschikbaar = $beschikbaar;
    }

    public function getId(): int { return $this->id; }
    public function getNaam(): string { return $this->naam; }
    public function getPrijs(): float { return $this->prijs; }
    public function getPromotieprijs(): ?float { return $this->promotieprijs; }
    public function getBeschrijving(): ?string { return $this->beschrijving; }
    public function isBeschikbaar(): bool { return $this->beschikbaar; }
}
