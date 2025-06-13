<?php

use Zero\System\Bootstrap\Application;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', __DIR__ . DS);
define('APP_DIR', ROOT_DIR . 'app' . DS);
define('DEBUG', false);  // true, false
define('ENV', 'development'); // development, testing, produciton, live

require_once ROOT_DIR . 'vendor'  . DS . 'autoload.php';

Application::run();