<?php

namespace Api;

use App\Avatar;
use PDOException;

header('Content-Type: application/json');

try {
    $avatar = new Avatar();
    echo $avatar->edit();
} catch (PDOException $e) {
    echo json_encode([
        'message' => $e->getMessage(),
        'status' => 500
    ]);
}
