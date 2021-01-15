<?php

namespace Api;

use App\Publications;

header('Content-Type: application/json');

if (count($_POST))
    echo Publications::create();

else if (isset($_GET['id']))
    echo Publications::findPublish($_GET['id']);

else
    echo Publications::getPublications();
