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

namespace Prooph\EventStoreClient;

use Prooph\EventStore\Exception\InvalidArgumentException;
use Prooph\EventStore\Exception\OutOfRangeException;
use Prooph\EventStore\Internal\Consts;

/**
 * All times are milliseconds
 *
 * @psalm-immutable
 */
final class ClusterSettings
{
    private string $clusterDns = '';
    private int $maxDiscoverAttempts = Consts::DEFAULT_MAX_CLUSTER_DISCOVER_ATTEMPTS;
    private int $externalGossipPort = 0;
    /** @var list<GossipSeed> */
    private array $gossipSeeds = [];
    private int $gossipTimeout = 0;
    private bool $preferRandomNode = false;

    public static function create(): ClusterSettingsBuilder
    {
        return new ClusterSettingsBuilder();
    }

    public static function fromGossipSeeds(
        array $gossipSeeds,
        int $maxDiscoverAttempts,
        int $gossipTimeout,
        bool $preferRandomNode
    ): self {
        $clusterSettings = new self();

        foreach ($gossipSeeds as $gossipSeed) {
            if (! $gossipSeed instanceof GossipSeed) {
                throw new InvalidArgumentException(\sprintf(
                    'Expected an array of %s',
                    GossipSeed::class
                ));
            }

            $clusterSettings->gossipSeeds[] = $gossipSeed;
        }

        $clusterSettings->maxDiscoverAttempts = $maxDiscoverAttempts;
        $clusterSettings->gossipTimeout = $gossipTimeout;
        $clusterSettings->preferRandomNode = $preferRandomNode;

        return $clusterSettings;
    }

    public static function fromClusterDns(
        string $clusterDns,
        int $maxDiscoverAttempts,
        int $externalGossipPort,
        int $gossipTimeout,
        bool $preferRandomNode
    ): self {
        $clusterSettings = new self();

        if (empty($clusterDns)) {
            throw new InvalidArgumentException(
                'Cluster DNS cannot be empty'
            );
        }

        if ($maxDiscoverAttempts < 1) {
            throw new OutOfRangeException(\sprintf(
                'Max discover attempts value is out of range: %d. Allowed range: [1, PHP_INT_MAX].',
                $maxDiscoverAttempts
            ));
        }

        if ($externalGossipPort < 1) {
            throw new OutOfRangeException(\sprintf(
                'External gossip port value is out of range: %d. Allowed range: [1, PHP_INT_MAX].',
                $externalGossipPort
            ));
        }

        $clusterSettings->clusterDns = $clusterDns;
        $clusterSettings->maxDiscoverAttempts = $maxDiscoverAttempts;
        $clusterSettings->externalGossipPort = $externalGossipPort;
        $clusterSettings->gossipTimeout = $gossipTimeout;
        $clusterSettings->preferRandomNode = $preferRandomNode;

        return $clusterSettings;
    }

    /** @psalm-pure */
    public function clusterDns(): string
    {
        return $this->clusterDns;
    }

    /** @psalm-pure */
    public function maxDiscoverAttempts(): int
    {
        return $this->maxDiscoverAttempts;
    }

    /** @psalm-pure */
    public function externalGossipPort(): int
    {
        return $this->externalGossipPort;
    }

    /**
     * @return list<GossipSeed>
     *
     * @psalm-pure
     */
    public function gossipSeeds(): array
    {
        return $this->gossipSeeds;
    }

    /** @psalm-pure */
    public function gossipTimeout(): int
    {
        return $this->gossipTimeout;
    }

    /** @psalm-pure */
    public function preferRandomNode(): bool
    {
        return $this->preferRandomNode;
    }
}
