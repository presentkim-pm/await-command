<?php

/**
 * Test-only stand-ins for the base classes of ext-pmmpthread, so that classes extending them
 * (AsyncTask, ThreadSafeLogger, ...) can be loaded, for example to create a test double of the Server.
 * Nothing is ever started on a thread. They are NEVER defined when the real extension is available.
 */

declare(strict_types=1);

namespace pmmp\thread;

abstract class ThreadSafe{
}

abstract class Runnable extends ThreadSafe{
}

abstract class Thread extends Runnable{
}

abstract class Worker extends Thread{
}
