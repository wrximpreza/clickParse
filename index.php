<?php
/**
 * Ivan Dyachuk
 * Website: #
 * Social profiles
 * Email: wrximpreza1987@gmail.com
 * Copyright (c) 2016. All rights
 */
if (!session_id()) @session_start();
ini_set("memory_limit",-1);
ini_set('max_execution_time', 30000);


require_once  __DIR__ . '/vendor/autoload.php';

/**
 * Root directories
 */
define('_ROOT', __DIR__);

/**
 * Call main methods
 */
$main = new Main();
$main->index();