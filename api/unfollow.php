<?php

namespace Api;

use App\Unfollow;
use PDOException;

header('Content-Type: application/json');

try {
    $unfollow = new Unfollow();
} catch (PDOException $e) {
    echo json_encode([
        'message' => $e->getMessage(),
        'status' => 500
    ]);
}
