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

use Generator;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\lang\Translatable;
use pocketmine\utils\TextFormat;
use SOFe\AwaitGenerator\Await;
use Throwable;

abstract class AwaitCommand extends Command{

    /** @param string[] $aliases */
    public function __construct(
        string $name,
        Translatable|string $description = "",
        Translatable|string|null $usage = null,
        array $aliases = []
    ){
        parent::__construct($name, $description, $usage, $aliases);
        $this->init();
    }

    public function init() : void{}

    /** @param string[] $args */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        Await::g2c(
            $this->onExecute($sender, $commandLabel, $args),
            null,
            [
                InvalidCommandSyntaxException::class => fn() => $this->onInvalidCommandSyntaxException($sender),
                "" => fn(Throwable $throwable) => $this->onUnhandledThrowable($sender, $throwable),
            ]
        );
    }

    private function onInvalidCommandSyntaxException(CommandSender $sender) : void{
        $sender->sendMessage(
            $sender->getLanguage()->translate(KnownTranslationFactory::commands_generic_usage($this->getUsage()))
        );
    }

    /**
     * Any other throwable escaping the generator is logged and reported to the sender, instead of being rethrown
     * from wherever the generator happened to be resumed (a scheduler task, a callback, ...) where nothing handles it.
     */
    private function onUnhandledThrowable(CommandSender $sender, Throwable $throwable) : void{
        $sender->getServer()->getLogger()->logException($throwable);
        $sender->sendMessage(TextFormat::RED . "An internal error occurred while attempting to perform this command");
    }

    /** @param string[] $args */
    abstract public function onExecute(CommandSender $sender, string $commandLabel, array $args) : Generator;
}
