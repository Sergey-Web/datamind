<?php

declare(strict_types=1);

namespace app\services\Search;

use app\fields\DocumentItemFields;
use app\services\Search\Clients\DocumentClient;
use yii\mongodb\Connection;

final class ImportToElasticService
{
    private const string INDEX = 'datamind_document_items';
    private const int BATCH_SIZE = 100;

    public function __construct(
        private Connection $mongo,
        private DocumentClient $elastic
    ) {}

    public function import(array $fields): int
    {
        $fields = array_values(array_intersect($fields, DocumentItemFields::ALL));

        if (!$fields) {
            throw new \InvalidArgumentException('No valid fields');
        }

        $collection = $this->mongo->getCollection('document_item');
        $cursor = $collection->find();

        $batch = [];
        $count = 0;

        foreach ($cursor as $doc) {
            $id = (string) $doc['_id'];
            unset($doc['_id']);

            $data = array_intersect_key($doc, array_flip($fields));

            $batch[] = [
                '_id' => $id,
                'data' => $data,
            ];

            if (count($batch) >= self::BATCH_SIZE) {
                $this->elastic->bulkIndex(self::INDEX, $batch);
                $count += count($batch);  
                $batch = [];
            }
        }

        if ($batch) {
            $this->elastic->bulkIndex(self::INDEX, $batch);
            $count += count($batch);  
        }

        return $count;
    }
}
