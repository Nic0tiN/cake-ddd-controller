<?php

use Cake\Core\Configure;

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
}
if (!defined('CORE_PATH')) {
    define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
}

if (!Configure::check('App.encoding')) {
    Configure::write('App.encoding', 'UTF-8');
}
