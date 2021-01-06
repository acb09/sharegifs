<?php

namespace Api;

use App\Followers;

header('Content-Type: application/json');

$followers = Followers::getFollowers();