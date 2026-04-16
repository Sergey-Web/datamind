<?php

declare(strict_types=1);

namespace app\services\Search\Clients;

use OpenSearch\Client;

readonly final class IndexClient
{
    public function __construct(
        private Client $client
    ) {
    }

    public function create(string $index, array $body): void
    {
        $this->client->indices()->create(['index' => $index, 'body' => $body]);
    }

    public function delete(string $index): void
    {
        $this->client->indices()->delete(['index' => $index]);
    }

    public function exists(string $index): bool
    {
        return $this->client->indices()->exists(['index' => $index]);
    }
}