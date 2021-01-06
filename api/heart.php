<?php

namespace Api;

use App\Heart;
use PDOException;

header('Content-Type: application/json');

try {
    $heart = new Heart();
} catch (PDOException $e) {
    echo json_encode([
        'message' => $e->getMessage(),
        'status' => 500
    ]);
}
