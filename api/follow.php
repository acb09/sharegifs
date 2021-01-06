<?php

namespace Api;

use App\Follow;
use PDOException;

header('Content-Type: application/json');

try {
    $follow = new Follow();
} catch (PDOException $e) {
    echo json_encode([
        'message' => $e->getMessage(),
        'status' => 500
    ]);
}
