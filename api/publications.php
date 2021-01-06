<?php

namespace Api;

use App\Publications;

header('Content-Type: application/json');

if (count($_POST))
    Publications::create();
else if (isset($_GET['id']))
    Publications::findPublish($_GET['id']);
else
    Publications::getPublications();
