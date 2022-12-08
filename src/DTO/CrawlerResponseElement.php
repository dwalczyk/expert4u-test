<?php

declare(strict_types=1);

namespace App\DTO;

final class CrawlerResponseElement
{
    public function __construct(
        public readonly string $tag,
        public readonly string $text
    ) {
    }
}
