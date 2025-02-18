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

namespace Prooph\EventStoreClient\SystemData;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class TcpFlags
{
    public const OPTIONS = [
        'None' => 0x00,
        'Authenticated' => 0x01,
        'TrustedWrite' => 0x02,
    ];

    public const NONE = 0x00;
    public const AUTHENTICATED = 0x01;
    public const TRUSTED_WRITE = 0x02;

    private string $name;
    private int $value;

    private function __construct(string $name)
    {
        $this->name = $name;
        $this->value = self::OPTIONS[$name];
    }

    public static function none(): self
    {
        return new self('None');
    }

    public static function authenticated(): self
    {
        return new self('Authenticated');
    }

    public static function trustedWrite(): self
    {
        return new self('TrustedWrite');
    }

    public static function fromName(string $value): self
    {
        if (! isset(self::OPTIONS[$value])) {
            throw new \InvalidArgumentException('Unknown enum name given');
        }

        return new self($value);
    }

    public static function fromValue(int $value): self
    {
        foreach (self::OPTIONS as $name => $v) {
            if ($v === $value) {
                return new self($name);
            }
        }

        throw new \InvalidArgumentException('Unknown enum value given');
    }

    /** @psalm-pure */
    public function equals(TcpFlags $other): bool
    {
        return $this->name === $other->name;
    }

    /** @psalm-pure */
    public function name(): string
    {
        return $this->name;
    }

    /** @psalm-pure */
    public function value(): int
    {
        return $this->value;
    }

    /** @psalm-pure */
    public function __toString(): string
    {
        return $this->name;
    }
}
