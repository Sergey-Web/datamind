<?php

declare(strict_types=1);

namespace app\commands;

use app\services\Search\DocumentItemMapper;
use PhpParser\Comment\Doc;
use Throwable;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class ImportController extends Controller
{
    public function actionCsvFileToMongo(): void
    {
        $this->stdout("Clear the collection document_item...\n", Console::FG_PURPLE);

        Yii::$app->mongodb
            ->getCollection('document_item')
            ->remove([]);

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
                $mapped = DocumentItemMapper::map($data);

                $batch[] = [...$mapped];

                if (count($batch) >= $batchSize) {
                    Yii::$app->mongodb->getCollection('document_item')->batchInsert($batch);

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

    private function combineSafe(array $headers, array $row): array
    {
        $result = [];

        foreach ($headers as $i => $header) {
            $result[$header] = $row[$i] ?? null;
        }

        return $result;
    }
}