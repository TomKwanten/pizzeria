<?php
// Business/ProductService.php
declare(strict_types=1);

namespace Business;

use Data\ProductDAO;

class ProductService {
    public function getAllProducts(): array {
        $dao = new ProductDAO();
        return $dao->getAll();
    }

    public function getProductById(int $id) {
        $dao = new ProductDAO();
        return $dao->getById($id);
    }
}
