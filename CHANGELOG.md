# Changelog

## 2.0.0 (2026-11-27)

The Poggit virion version for the original [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) 5.x is kept on the
[`legacy/pmmp-5.44`](https://github.com/presentkim-pm/await-command/tree/legacy/pmmp-5.44) branch.

### ⚠️ Breaking changes
- Targets [axolotl-pm/PocketMine-MP](https://github.com/axolotl-pm/PocketMine-MP) and **PHP 8.4+**.
- Distributed with Composer: the Poggit virion files (`.poggit.yml`, `virion.yml`) are removed.
  [await-generator](https://github.com/SOF3/await-generator) is the Composer package `sof3/await-generator`
  (`^3.6.1`) instead of a bundled virion.
- An exception that is neither handled in the generator nor an `InvalidCommandSyntaxException` used to be rethrown as an
  `AwaitException` from wherever the generator was resumed. It is now logged and reported to the sender.

### 🐛 Fixes
- `AwaitPluginCommand` assigned its plugin after the parent constructor, so `getOwningPlugin()` failed with an uninitialized
  property error inside `init()`. The plugin is now assigned first.
- `string $usage = null` was an implicit nullable parameter (deprecated in PHP 8.4). The constructors take
  `Translatable|string|null $usage` and `Translatable|string $description`, like `Command`.
- An unhandled exception thrown after the generator had been suspended (for example from a scheduler task) escaped into the
  scheduler. It is now caught, logged and reported.

### 🧪 Tests
- PHPUnit tests (generator driven with `Await`, mocked `CommandSender` / `Plugin`) and GitHub Actions CI on PHP 8.4 / 8.5.
