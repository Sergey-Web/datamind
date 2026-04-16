<?php

declare(strict_types=1);

namespace app\models;

use app\fields\DocumentItemFields;
use yii\mongodb\ActiveRecord;

final class DocumentItem extends ActiveRecord
{
    public static function collectionName(): array
    {
        return ['datamind', 'document_item'];
    }

    public function attributes(): array
    {
        return array_merge(['_id'], DocumentItemFields::ALL);
    }

    public function rules(): array
    {
        return [
            [
                [
                    'firm',
                    'region',
                    'city',
                    'delivery_address',
                    'client_address',
                    'client_name',
                    'license',
                    'barcode',
                    'product_name',
                    'unit',
                    'manufacturer',
                    'supplier',
                    'warehouse',
                ],
                'string',
            ],
            [
                [
                    'client_code',
                    'client_division_code',
                    'client_okpo',
                    'product_code',
                    'morion_code',
                    'quantity',
                ],
                'integer',
            ],
            [
                [
                    'invoice_date',
                    'license_expire_date',
                ],
                'safe',
            ],
        ];
    }
}
