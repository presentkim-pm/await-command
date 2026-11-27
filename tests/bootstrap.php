<?php

declare(strict_types=1);

/*
 * PocketMine-MP's CoreConstants.php (loaded by the Composer autoloader, which PHPUnit includes before this file)
 * expects its data packages under its own vendor/ directory, as when the server is the root project.
 * Installed as a dev dependency they live in our vendor/, so point the server's vendor/ there.
 */
$vendor = dirname(__DIR__) . '/vendor';
$serverVendor = $vendor . '/axolotl-pm/pocketmine-mp/vendor';
if(!file_exists($serverVendor) && !@symlink('../..', $serverVendor) && !is_dir($serverVendor)){
    throw new \RuntimeException("Could not link $serverVendor to $vendor");
}

require_once $vendor . '/autoload.php';

if(!extension_loaded('pmmpthread')){
    // Test-only stand-in for the ext-pmmpthread base classes, see the file for its limits
    require __DIR__ . '/polyfill/pmmpthread.php';
}
