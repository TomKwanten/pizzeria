<?php
// Business/KlantService.php
declare(strict_types=1);

namespace Business;

use Data\KlantDAO;
use Entities\Klant;

class KlantService {
    private KlantDAO $dao;

    public function __construct() {
        $this->dao = new KlantDAO();
    }

    public function registreer(Klant $klant): Klant {
        return $this->dao->register($klant);
    }

    public function login(string $email, string $wachtwoord): Klant {
        return $this->dao->login($email, $wachtwoord);
    }
}
