<?php

declare(strict_types=1);

namespace app\services\Search\Clients;

use Elastic\Elasticsearch\Client;

readonly final class DocumentClient
{
    public function __construct(
        private Client $client
    ) {
    }

    public function index(string $index, array $body): void
    {
        $this->client->index(['index' => $index, 'body' => $body]);
    }
}