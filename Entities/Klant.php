<?php
// Entities/Klant.php
declare(strict_types=1);

namespace Entities;

use Exceptions\OngeldigEmailadresException;
use Exceptions\WachtwoordenKomenNietOvereenException;

class Klant {
    private ?int $id;
    private ?string $voornaam = null;
    private ?string $naam = null;
    private ?string $straat = null;
    private ?string $huisnummer = null;
    private ?string $postcode = null;
    private ?string $gemeente = null;
    private ?string $telefoon = null;
    private ?string $email = null;
    private ?string $wachtwoord = null;
    private ?bool $promotie = null;
    private ?string $opmerkingen = null;

    public function __construct(?int $id = null) {
        $this->id = $id;
    }

    // Getters en setters
    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function getWachtwoord(): ?string { return $this->wachtwoord; }
    public function getVoornaam(): ?string { return $this->voornaam; }
    public function getNaam(): ?string { return $this->naam; }
    public function getStraat(): ?string { return $this->straat; }
    public function getHuisnummer(): ?string { return $this->huisnummer; }
    public function getPostcode(): ?string { return $this->postcode; }
    public function getGemeente(): ?string { return $this->gemeente; }
    public function getTelefoon(): ?string { return $this->telefoon; }
    public function getPromotie(): ?bool { return $this->promotie; }
    public function getOpmerkingen(): ?string { return $this->opmerkingen; }

    public function setEmail(string $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new OngeldigEmailadresException();
        }
        $this->email = $email;
    }

    public function setWachtwoord(string $wachtwoord, string $herhaalwachtwoord) {
        if ($wachtwoord !== $herhaalwachtwoord) {
            throw new WachtwoordenKomenNietOvereenException();
        }
        $this->wachtwoord = $wachtwoord;
    }

    // overige setters...
    public function setVoornaam(?string $voornaam) { $this->voornaam = $voornaam; }
    public function setNaam(?string $naam) { $this->naam = $naam; }
    public function setStraat(?string $straat) { $this->straat = $straat; }
    public function setHuisnummer(?string $huisnummer) { $this->huisnummer = $huisnummer; }
    public function setPostcode(?string $postcode) { $this->postcode = $postcode; }
    public function setGemeente(?string $gemeente) { $this->gemeente = $gemeente; }
    public function setTelefoon(?string $telefoon) { $this->telefoon = $telefoon; }
    public function setPromotie(?bool $promotie) { $this->promotie = $promotie; }
    public function setOpmerkingen(?string $opmerkingen) { $this->opmerkingen = $opmerkingen; }
}
