<?php

declare(strict_types=1);

namespace app\commands;

use app\models\DocumentItem;
use yii\console\Controller;
use yii\helpers\Console;

final class ReportController extends Controller
{
    public function actionSumQuantityByRegionAndProduct(): void
    {
        $this->stdout("Report: Quantity by region and product\n\n", Console::FG_GREEN);

        $rows = DocumentItem::getCollection()->aggregate([
            [
                '$group' => [
                    '_id' => [
                        'region' => '$region',
                        'product_name' => '$product_name',
                    ],
                    'total_quantity' => ['$sum' => '$quantity'],
                ],
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'region' => '$_id.region',
                    'product_name' => '$_id.product_name',
                    'total_quantity' => 1,
                ],
            ],
            [
                '$sort' => ['total_quantity' => 1],
            ],
        ]);

        foreach ($rows as $row) { 
            $this->stdout(
                sprintf(
                    "%-20s | %-50s | %5d\n",
                    $row['region'],
                    $row['product_name'],
                    $row['total_quantity']
                )
            );
        }
    }
}