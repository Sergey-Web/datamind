<?php

declare(strict_types=1);

namespace app\commands;

use app\mappers\DocumentItemMapper;
use app\services\Search\ImportToElasticService;
use Throwable;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\mongodb\Connection;

class ImportController extends Controller
{
    public function actionCsvFileToMongo(): void
    {
        $this->stdout("Clear the collection document_item...\n", Console::FG_PURPLE);

        /** @var Connection $mongodb */
        $mongodb = Yii::$app->mongodb;
        $mongodb->getCollection('document_item')->remove([]);

        $this->stdout("Import started...\n", Console::FG_YELLOW);
        $file = 'web/uploads/data.csv';
        $batch = [];

        try {
            $handle = fopen($file, 'r');
            $batchSize = 1000;
            $headers = array_map(function ($header) {
                $header = trim($header);
                $header = mb_strtolower($header);
                $header = str_replace(['.', '/', '\\'], '_', $header);
                $header = preg_replace('/\s+/u', '_', $header);
                $header = preg_replace('/_+/', '_', $header);

                return trim($header, '_');
            }, fgetcsv($handle, 0, ',', '"', '\\') ?? []);

            while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                $data = $this->combineSafe($headers, $row);
                $mapped = new DocumentItemMapper()->map($data);

                $batch[] = [...$mapped];

                if (count($batch) >= $batchSize) {
                    $mongodb->getCollection('document_item')->batchInsert($batch);

                    $batch = [];
                }
            }

            if ($batch) {
                Yii::$app->mongodb->getCollection('document_item')->batchInsert($batch);
            }

            fclose($handle);
        } catch (Throwable $e) {
            $this->stderr("Import failed: " . $e->getMessage() . "\n", Console::FG_RED);
            return;
        }
        
        $this->stdout('Import completed successfully - ' . filesize($file) . " bytes\n", Console::FG_GREEN);
    }

    public function actionSyncMongoToElastic(string $fields): void
    {
        $fields = array_map('trim', explode(',', $fields));

        /** @var ImportToElasticService $service */
        $service = Yii::$container->get(ImportToElasticService::class);
        $this->stdout('Imported ' .$service->import($fields) . ' records.' . "\n");
    }

    private function combineSafe(array $headers, array $row): array
    {
        $result = [];

        foreach ($headers as $i => $header) {
            $result[$header] = $row[$i] ?? null;
        }

        return $result;
    }
}