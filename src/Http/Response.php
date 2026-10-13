<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Http;

use JsonException;

final class Response
{
    public function __construct(
        private readonly string $body,
        private readonly int $status = 200,
        private readonly array $headers = [],
    ) {
    }

    public static function html(string $body, int $status = 200, array $headers = []): self
    {
        return new self($body, $status, ['Content-Type' => 'text/html; charset=utf-8', ...$headers]);
    }

    /**
     * @throws JsonException
     */
    public static function json(array $data, int $status = 200, array $headers = []): self
    {
        return new self(
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            $status,
            ['Content-Type' => 'application/json; charset=utf-8', ...$headers],
        );
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }

        echo $this->body;
    }
}
