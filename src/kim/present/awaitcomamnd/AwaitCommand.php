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

namespace kim\present\awaitcomamnd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use SOFe\AwaitGenerator\Await;

abstract class AwaitCommand extends Command{

    /** @param string[] $args */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        Await::g2c(
            $this->onExecute($sender, $commandLabel, $args),
            null,
            [InvalidCommandSyntaxException::class => fn() => $this->onInvalidCommandSyntaxException($sender)]
        );
    }

    private function onInvalidCommandSyntaxException(CommandSender $sender) : void{
        $sender->sendMessage(
            $sender->getLanguage()->translate(KnownTranslationFactory::commands_generic_usage($this->getUsage()))
        );
    }

    /** @param string[] $args */
    abstract public function onExecute(CommandSender $sender, string $commandLabel, array $args) : \Generator;
}
