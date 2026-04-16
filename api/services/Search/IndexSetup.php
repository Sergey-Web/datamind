<?php

declare(strict_types=1);

namespace app\services\Search;

use app\services\Search\Clients\IndexClient;

readonly final class IndexSetup
{
    public const string INDEX_NAME = 'datamind_document_items';

    public function __construct(
        private IndexClient $indexClient
    ) {
    }

    public function recreate(int $shards = 1): void
    {
        if ($this->indexClient->exists(self::INDEX_NAME)) {
            $this->indexClient->delete(self::INDEX_NAME);
        }

        $this->indexClient->create(self::INDEX_NAME, [
            'settings' => $this->getSettings($shards),
            'mappings' => $this->getMappings(),
        ]);
    }

    private function getSettings(int $shards): array 
    {
        return [
            'number_of_shards' => $shards,
            'analysis' => [
                'analyzer' => [
                    'default_search_index' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => ['lowercase', 'asciifolding'],
                    ],
                    'default_search_query' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => ['lowercase', 'asciifolding'],
                    ],
                ],
            ],
        ];
    }

    private function getMappings(): array
    {
        return [
            'dynamic' => false,
            'properties' => [
                'firm' => [
                    'type' => 'text',
                    'analyzer' => 'default_search_index',
                    'search_analyzer' => 'default_search_query',
                    'fields' => [
                        'keyword' => [
                            'type' => 'keyword',
                        ],
                    ],
                ],
                'region' => [
                    'type' => 'keyword',
                ],
                'city' => [
                    'type' => 'keyword',
                ],
                'invoice_date' => [
                    'type' => 'date',
                    'format' => 'strict_date_optional_time||epoch_millis',
                ],
                'fact_address' => [
                    'type' => 'text',
                    'analyzer' => 'default_search_index',
                    'fields' => [
                        'keyword' => [
                            'type' => 'keyword',
                        ],
                    ],
                ],
                'legal_address' => [
                    'type' => 'text',
                    'analyzer' => 'default_search_index',
                    'fields' => [
                        'keyword' => [
                            'type' => 'keyword',
                        ],
                    ],
                ],
                'client' => [
                    'type' => 'text',
                    'analyzer' => 'default_search_index',
                    'fields' => [
                        'keyword' => [
                            'type' => 'keyword',
                        ],
                    ],
                ],
                'client_code' => [
                    'type' => 'integer',
                ],
                'client_sub_code' => [
                    'type' => 'long',
                ],
                'client_okpo' => [
                    'type' => 'integer',
                ],
                'license' => [
                    'type' => 'keyword',
                ],
                'license_expiration_date' => [
                    'type' => 'date',
                    'format' => 'strict_date_optional_time||epoch_millis',
                ],
                'product_code' => [
                    'type' => 'integer',
                ],
                'barcode' => [
                    'type' => 'keyword',
                ],
                'product' => [
                    'type' => 'text',
                    'analyzer' => 'default_search_index',
                    'search_analyzer' => 'default_search_query',
                    'fields' => [
                        'keyword' => [
                            'type' => 'keyword',
                        ],
                    ],
                ],
                'morion_code' => [
                    'type' => 'integer',
                ],
                'unit' => [
                    'type' => 'keyword',
                ],
                'manufacturer' => [
                    'type' => 'text',
                    'analyzer' => 'default_search_index',
                    'fields' => [
                        'keyword' => [
                            'type' => 'keyword',
                        ],
                    ],
                ],
                'supplier' => [
                    'type' => 'text',
                    'analyzer' => 'default_search_index',
                    'fields' => [
                        'keyword' => [
                            'type' => 'keyword',
                        ],
                    ],
                ],
                'quantity' => [
                    'type' => 'integer',
                ],
                'warehouse' => [
                    'type' => 'keyword',
                ],
            ],
        ];
    }
}
