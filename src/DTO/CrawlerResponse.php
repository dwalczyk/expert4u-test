<?php

declare(strict_types=1);

namespace App\DTO;

final class CrawlerResponse implements \JsonSerializable
{
    /**
     * @param CrawlerResponseElement[] $elements
     */
    public function __construct(
        public readonly string $url,
        public readonly int $httpResponseCode,
        public readonly array $elements,
    ) {
    }

    public function jsonSerialize(): array
    {
        $properties = [];
        foreach (\get_object_vars($this) as $name => $value) {
            $properties[$name] = $value;
        }

        return $properties;
    }
}
