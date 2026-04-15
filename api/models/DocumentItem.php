<?php

declare(strict_types=1);

namespace app\models;

use yii\mongodb\ActiveRecord;

final class DocumentItem extends ActiveRecord
{
    public static function collectionName(): array
    {
        return ['documents', 'document_item'];
    }

    public function attributes(): array
    {
        return [
            '_id',
            'firm',
            'region',
            'city',
            'invoice_date',
            'delivery_address',
            'client_address',
            'client_name',
            'client_code',
            'client_division_code',
            'client_okpo',
            'license',
            'license_expire_date',
            'product_code',
            'barcode',
            'product_name',
            'morion_code',
            'unit',
            'manufacturer',
            'supplier',
            'quantity',
            'warehouse',
        ];
    }
}
