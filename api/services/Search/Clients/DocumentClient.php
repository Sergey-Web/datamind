<?php

declare(strict_types=1);

namespace app\services\Search\Clients;

use OpenSearch\Client;

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

    public function bulkIndex(string $index, array $documents): array
    {
        $body = [];

        foreach ($documents as $doc) {
            $body[] = [
                'index' => [
                    '_index' => $index,
                    '_id' => $doc['_id'],
                ]
            ];

            $body[] = $doc['data'];
        }

        return $this->client->bulk([
            'body' => $body,
        ]);
    }
}
