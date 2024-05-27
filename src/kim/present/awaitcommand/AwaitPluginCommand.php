<?php

/**
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author       PresentKim (debe3721@gmail.com)
 * @link         https://github.com/PresentKim
 * @license      https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace kim\present\awaitcommand;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

/** @phpstan-template TPlugin of Plugin */
abstract class AwaitPluginCommand extends AwaitCommand implements PluginOwned{
    /**
     * @var Plugin
     * @phpstan-var TPlugin
     */
    protected Plugin $plugin;

    /**
     * @phpstan-param TPlugin $plugin
     *
     * @param string[]        $aliases
     */
    public function __construct(
        Plugin $plugin, string $name, string $description = "", string $usage = null, array $aliases = []
    ){
        parent::__construct($name, $description, $usage, $aliases);
        $this->plugin = $plugin;
    }

    /** @phpstan-return TPlugin */
    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}
