<!-- PROJECT BADGES -->
<div align="center">

[![Poggit CI][poggit-ci-badge]][poggit-ci-url]
[![Stars][stars-badge]][stars-url]
[![License][license-badge]][license-url]

</div>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <img src="https://raw.githubusercontent.com/presentkim-pm/await-command/main/assets/icon.png" alt="Logo" width="80" height="80"/>
  <h3>await-command</h3>
  <p align="center">
    Provides generator-base command class for await-generator!

[View in Poggit][poggit-ci-url] · [Report a bug][issues-url] · [Request a feature][issues-url]

  </p>
</div>


<!-- ABOUT THE PROJECT -->

## About The Project

:heavy_check_mark: Provides generator-base command class for await-generator!

- `kim\present\awaitcommand\AwaitCommand`

:heavy_check_mark: Provides implementation versions of PluginOwned!

- `kim\present\awaitcommand\AwaitPluginCommand`

-----

## Installation

See [Official Poggit Virion Documentation](https://github.com/poggit/support/blob/master/virion.md)

-----

## How to use?

Just extend `AwaitCommand` or `AwaitPluginCommand` instead of `Command`!  
And implement `onExecute` method with `Generator` return type!

### AwaitCommand

````php
<?php

use kim\present\awaitcommand\AwaitCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

class TestCommand extends AwaitCommand{
    public function __construct(){
        parent::__construct("test", "test command", "/test usage", ["t"]);
    }

    public function onExecute(CommandSender $sender, string $commandLabel, array $args) : \Generator{
        /** @var TestObject[] $rank */
        $test = yield from TestFactory::getTestObject();

        $sender->sendMessage("TestObject: " . $test->getName());
    }
}
````

### AwaitPluginCommand

````php
<?php

use kim\present\awaitcommand\AwaitPluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

/** @extends AwaitPluginCommand<TestPLugin> */
class TestCommand extends AwaitPluginCommand{
    public function __construct(Plugin $plugin){
        parent::__construct($plugin, "test", "test command", "/test usage", ["t"]);
    }

    public function onExecute(CommandSender $sender, string $commandLabel, array $args) : \Generator{
        // ...
    }
}
````

-----

## License

Distributed under the **MIT**. See [LICENSE][license-url] for more information


[poggit-ci-badge]: https://poggit.pmmp.io/ci.shield/presentkim-pm/await-command/await-command?style=for-the-badge

[stars-badge]: https://img.shields.io/github/stars/presentkim-pm/await-command.svg?style=for-the-badge

[license-badge]: https://img.shields.io/github/license/presentkim-pm/await-command.svg?style=for-the-badge

[poggit-ci-url]: https://poggit.pmmp.io/ci/presentkim-pm/await-command/await-command

[stars-url]: https://github.com/presentkim-pm/await-command/stargazers

[issues-url]: https://github.com/presentkim-pm/await-command/issues

[license-url]: https://github.com/presentkim-pm/await-command/blob/main/LICENSE

[project-icon]: https://raw.githubusercontent.com/presentkim-pm/await-command/main/assets/icon.png
