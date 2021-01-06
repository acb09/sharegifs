<?php

namespace Api;

use App\Login;

Login::logout();

header('Location: /login');