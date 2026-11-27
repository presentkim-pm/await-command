<?php

declare(strict_types=1);

namespace kim\present\awaitcommand\tests;

use Generator;
use kim\present\awaitcommand\AwaitCommand;
use kim\present\awaitcommand\AwaitPluginCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\Language;
use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\promise\PromiseResolver;
use pocketmine\thread\log\AttachableThreadSafeLogger;
use pocketmine\Server;
use SOFe\AwaitGenerator\Await;

#[CoversClass(AwaitCommand::class)]
#[CoversClass(AwaitPluginCommand::class)]
final class AwaitCommandTest extends TestCase{

    /** @var list<string> */
    private array $messages = [];
    /** @var list<\Throwable> */
    private array $logged = [];

    private function createSender() : CommandSender{
        $language = $this->createStub(Language::class);
        $language->method('translate')->willReturnCallback(
            static fn(Translatable $t) : string => $t->getText() . "|" . implode(",", array_map(strval(...), $t->getParameters()))
        );

        $logger = $this->createStub(AttachableThreadSafeLogger::class);
        $logger->method('logException')->willReturnCallback(function(\Throwable $e) : void{
            $this->logged[] = $e;
        });
        $server = $this->createStub(Server::class);
        $server->method('getLogger')->willReturn($logger);

        $sender = $this->createStub(CommandSender::class);
        $sender->method('getLanguage')->willReturn($language);
        $sender->method('getServer')->willReturn($server);
        $sender->method('sendMessage')->willReturnCallback(function(Translatable|string $m) : void{
            $this->messages[] = (string) $m;
        });
        return $sender;
    }

    /** @param \Closure(CommandSender, string, array): Generator $body */
    private function createCommand(\Closure $body) : AwaitCommand{
        return new class("test", "desc", "/test <x>", ["t"], $body) extends AwaitCommand{
            public function __construct(string $n, string $d, string $u, array $a, private readonly \Closure $body){
                parent::__construct($n, $d, $u, $a);
            }

            public function onExecute(CommandSender $sender, string $commandLabel, array $args) : Generator{
                return yield from ($this->body)($sender, $commandLabel, $args);
            }
        };
    }

    public function testConstructorAcceptsNullUsageAndCallsInit() : void{
        $command = new class("foo") extends AwaitCommand{
            public bool $inited = false;

            public function init() : void{
                $this->inited = true;
            }

            public function onExecute(CommandSender $sender, string $commandLabel, array $args) : Generator{
                yield from [];
            }
        };
        self::assertTrue($command->inited);
        self::assertSame("/foo", $command->getUsage());
    }

    public function testConstructorAcceptsTranslatable() : void{
        $description = new Translatable("some.key");
        $command = new class("foo", $description) extends AwaitCommand{
            public function onExecute(CommandSender $sender, string $commandLabel, array $args) : Generator{
                yield from [];
            }
        };
        self::assertSame($description, $command->getDescription());
    }

    public function testExecutePassesArguments() : void{
        $seen = null;
        $command = $this->createCommand(function(CommandSender $s, string $label, array $args) use (&$seen) : Generator{
            $seen = [$label, $args];
            yield from [];
        });
        $command->execute($this->createSender(), "t", ["a", "b"]);
        self::assertSame(["t", ["a", "b"]], $seen);
    }

    public function testExecuteResumesAfterAsyncWait() : void{
        $resolver = new PromiseResolver();
        $sender = $this->createSender();
        $command = $this->createCommand(function(CommandSender $s) use ($resolver) : Generator{
            $value = yield from Await::promise(fn($resolve) => $resolver->getPromise()->onCompletion($resolve, static fn() => null));
            $s->sendMessage("got $value");
        });
        $command->execute($sender, "test", []);
        self::assertSame([], $this->messages);
        $resolver->resolve("42");
        self::assertSame(["got 42"], $this->messages);
    }

    public function testInvalidSyntaxSendsUsageSynchronously() : void{
        $command = $this->createCommand(function() : Generator{
            yield from [];
            throw new InvalidCommandSyntaxException();
        });
        $command->execute($this->createSender(), "test", []);
        self::assertCount(1, $this->messages);
        self::assertStringContainsString("/test <x>", $this->messages[0]);
        self::assertSame([], $this->logged);
    }

    public function testInvalidSyntaxSendsUsageAfterAsyncWait() : void{
        $resolver = new PromiseResolver();
        $command = $this->createCommand(function() use ($resolver) : Generator{
            yield from Await::promise(fn($resolve) => $resolver->getPromise()->onCompletion($resolve, static fn() => null));
            throw new InvalidCommandSyntaxException();
        });
        $command->execute($this->createSender(), "test", []);
        self::assertSame([], $this->messages);
        $resolver->resolve(null);
        self::assertCount(1, $this->messages);
        self::assertStringContainsString("/test <x>", $this->messages[0]);
    }

    public function testUnhandledThrowableIsLoggedNotRethrown() : void{
        $error = new \RuntimeException("boom");
        $command = $this->createCommand(function() use ($error) : Generator{
            yield from [];
            throw $error;
        });
        $command->execute($this->createSender(), "test", []);
        self::assertSame([$error], $this->logged);
        self::assertCount(1, $this->messages);
        self::assertStringContainsString("internal error", $this->messages[0]);
    }

    public function testUnhandledThrowableAfterAsyncWaitIsLoggedNotRethrown() : void{
        $resolver = new PromiseResolver();
        $error = new \RuntimeException("late boom");
        $command = $this->createCommand(function() use ($resolver, $error) : Generator{
            yield from Await::promise(fn($resolve) => $resolver->getPromise()->onCompletion($resolve, static fn() => null));
            throw $error;
        });
        $command->execute($this->createSender(), "test", []);
        $resolver->resolve(null); // must not throw out of the resolver
        self::assertSame([$error], $this->logged);
    }

    public function testPluginCommandOwnerIsAvailableInInit() : void{
        $plugin = $this->createStub(PluginBase::class);
        $command = new class($plugin, "p") extends AwaitPluginCommand{
            public ?Plugin $ownerInInit = null;

            public function init() : void{
                $this->ownerInInit = $this->getOwningPlugin();
            }

            public function onExecute(CommandSender $sender, string $commandLabel, array $args) : Generator{
                yield from [];
            }
        };
        self::assertSame($plugin, $command->getOwningPlugin());
        self::assertSame($plugin, $command->ownerInInit);
    }
}
