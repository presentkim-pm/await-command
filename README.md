<!-- PROJECT BADGES -->
<div align="center">

[![Packagist][packagist-badge]][packagist-url]
[![CI][ci-badge]][ci-url]
[![Stars][stars-badge]][stars-url]
[![License][license-badge]][license-url]

</div>


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <img src="https://raw.githubusercontent.com/presentkim-pm/await-command/main/assets/icon.png" alt="Logo" width="80" height="80"/>
  <h3>await-command</h3>
  <p align="center">
    Generator based command classes for <a href="https://github.com/SOF3/await-generator">await-generator</a>: write <code>onExecute()</code> as a generator.

[Packagist][packagist-url] · [Changelog](CHANGELOG.md) · [Report a bug][issues-url] · [Request a feature][issues-url]

  </p>
</div>

```php
class TestCommand extends AwaitCommand{
    public function __construct(){
        parent::__construct("test", "test command", "/test usage", ["t"]);
    }

    public function onExecute(CommandSender $sender, string $commandLabel, array $args) : \Generator{
        $test = yield from TestFactory::getTestObject();
        $sender->sendMessage("TestObject: " . $test->getName());
    }
}
```

-----

## 🌟 Why await-command?

Commands that wait for a database, a form or a timer turn into nested callbacks. With await-command the command body
is straight-line code.

- **Linear** : extend `AwaitCommand`, implement `onExecute()` as a `Generator`, `yield from` whatever you wait for
- **Usage errors for free** : throw `InvalidCommandSyntaxException` anywhere in the generator, even after a wait, and the sender gets the usage message
- **Safe** : any other throwable is logged and reported to the sender instead of escaping into a scheduler task or callback
- **Plugin owned** : `AwaitPluginCommand` implements `PluginOwned` and keeps the plugin for you
- **Tested** : on PHP 8.4 / 8.5

-----

## 📦 Installation

```sh
composer require presentkim/await-command
```

This also installs [`sof3/await-generator`](https://packagist.org/packages/sof3/await-generator).

| Requirement | Version                                                           |
|-------------|-------------------------------------------------------------------|
| Server      | [axolotl-pm/PocketMine-MP][axolotl-url] (fork of PocketMine-MP 5) |
| PHP         | **8.4** or newer                                                  |

> The Poggit virion for the original PocketMine-MP 5.44 and older is kept on the
> [`legacy/pmmp-5.44`][legacy-url] branch (no longer maintained).

-----

## 🚀 Quick start

1. Extend `AwaitCommand` (or `AwaitPluginCommand` when the command belongs to a plugin)
2. Implement `onExecute()` with a `Generator` return type
3. Register the command as usual (`$commandMap->register()`)

```php
use kim\present\awaitcommand\AwaitCommand;
use pocketmine\command\CommandSender;

class TestCommand extends AwaitCommand{
    public function __construct(){
        parent::__construct("test", "test command", "/test usage", ["t"]);
    }

    public function onExecute(CommandSender $sender, string $commandLabel, array $args) : \Generator{
        $test = yield from TestFactory::getTestObject();

        $sender->sendMessage("TestObject: " . $test->getName());
    }
}
```

<details>
<summary><b>AwaitPluginCommand</b></summary>

```php
use kim\present\awaitcommand\AwaitPluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

/** @extends AwaitPluginCommand<TestPlugin> */
class TestCommand extends AwaitPluginCommand{
    public function __construct(Plugin $plugin){
        parent::__construct($plugin, "test", "test command", "/test usage", ["t"]);
    }

    public function init() : void{
        // getOwningPlugin() is already available here
    }

    public function onExecute(CommandSender $sender, string $commandLabel, array $args) : \Generator{
        // ...
    }
}
```

</details>

<details>
<summary><b>Errors</b></summary>

- `throw new InvalidCommandSyntaxException()` sends `commands.generic.usage` with the command usage to the sender
- any other throwable is logged with the server logger and the sender gets "An internal error occurred while attempting to perform this command"
- handle your own errors with `try / catch` inside the generator

</details>

-----

## 📖 API reference

| Member                                                                              | Description                                                    |
|-------------------------------------------------------------------------------------|----------------------------------------------------------------|
| `AwaitCommand::__construct(name, description = "", usage = null, aliases = [])`     | Same arguments as `Command` (`Translatable` or string)         |
| `AwaitCommand::onExecute(CommandSender, string $commandLabel, array $args) : Generator` | Abstract, the command body                                 |
| `AwaitCommand::init() : void`                                                       | Optional hook called at the end of the constructor             |
| `AwaitPluginCommand::__construct(Plugin $plugin, name, ...)`                        | `AwaitCommand` that implements `PluginOwned`                   |
| `AwaitPluginCommand::getOwningPlugin() : Plugin`                                    | The plugin given to the constructor                            |

Permissions are checked by the command map before `execute()` is called, as for every other command.

-----

## 🧪 Development

```sh
composer install --ignore-platform-req='ext-*'
composer test
```

-----

## 📄 License

Distributed under the **MIT** License. See [LICENSE][license-url] for more information.


[packagist-badge]: https://img.shields.io/packagist/v/presentkim/await-command.svg?style=for-the-badge

[packagist-url]: https://packagist.org/packages/presentkim/await-command

[ci-badge]: https://img.shields.io/github/actions/workflow/status/presentkim-pm/await-command/ci.yml?branch=main&style=for-the-badge

[ci-url]: https://github.com/presentkim-pm/await-command/actions/workflows/ci.yml

[stars-badge]: https://img.shields.io/github/stars/presentkim-pm/await-command.svg?style=for-the-badge

[license-badge]: https://img.shields.io/github/license/presentkim-pm/await-command.svg?style=for-the-badge

[stars-url]: https://github.com/presentkim-pm/await-command/stargazers

[issues-url]: https://github.com/presentkim-pm/await-command/issues

[license-url]: https://github.com/presentkim-pm/await-command/blob/main/LICENSE

[legacy-url]: https://github.com/presentkim-pm/await-command/tree/legacy/pmmp-5.44

[axolotl-url]: https://github.com/axolotl-pm/PocketMine-MP
