<?php

/**
 * This file is part of `prooph/event-store-client`.
 * (c) 2018-2021 Alexander Miertsch <kontakt@codeliner.ws>
 * (c) 2018-2021 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ProophTest\EventStoreClient;

use function Amp\call;
use Amp\Promise;
use Amp\Success;
use Closure;
use Generator;
use Prooph\EventStore\Async\EventStoreConnection;
use ProophTest\EventStoreClient\Helper\TestConnection;

trait SpecificationWithConnection
{
    protected EventStoreConnection $connection;

    protected function given(): Generator
    {
        yield new Success();
    }

    protected function when(): Generator
    {
        yield new Success();
    }

    protected function execute(Closure $test): Promise
    {
        return call(function () use ($test): Generator {
            $this->connection = TestConnection::create();

            yield $this->connection->connectAsync();

            try {
                yield from $this->given();

                yield from $this->when();

                yield from $test();
            } finally {
                yield from $this->end();
            }
        });
    }

    protected function end(): Generator
    {
        $this->connection->close();

        yield new Success();
    }
}
