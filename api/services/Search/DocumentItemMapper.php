<?php

declare(strict_types=1);

namespace app\services\Search;

use DateTimeImmutable;

final class DocumentItemMapper
{
    private const HEADER_MAP = [
        'фирма' => 'firm',
        'область' => 'region',
        'город' => 'city',
        'дата_накл' => 'invoice_date',

        'факт_адрес_доставки' => 'delivery_address',
        'юр_адрес_клиента' => 'client_address',

        'клиент' => 'client_name',
        'код_клиента' => 'client_code',
        'код_подразд_кл' => 'client_division_code',
        'окпо_клиента' => 'client_okpo',

        'лицензия' => 'license',
        'дата_окончания_лицензии' => 'license_expire_date',

        'код_товара' => 'product_code',
        'штрих-код_товара' => 'barcode',
        'товар' => 'product_name',
        'код_мориона' => 'morion_code',

        'еи' => 'unit',
        'производитель' => 'manufacturer',
        'поставщик' => 'supplier',

        'количество' => 'quantity',
        'склад_филиал' => 'warehouse',
    ];

    public static function map(array $row): array
    {
        $result = [];

        foreach (self::HEADER_MAP as $csvKey => $field) {
            $value = $row[$csvKey] ?? null;

            $result[$field] = self::castValue($field, $value);
        }

        return $result;
    }

    private static function castValue(string $field, mixed $value): mixed
    {
        return match ($field) {
            'client_code',
            'client_division_code',
            'client_okpo',
            'product_code',
            'morion_code',
            'quantity' => (int) $value,

            'invoice_date',
            'license_expire_date' => self::parseDate($value),

            default => $value,
        };
    }

    private static function parseDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $dt = DateTimeImmutable::createFromFormat('m/d/Y', $date);

        if ($dt && $dt->format('m/d/Y') === $date) {
            return $dt->format('Y-m-d');
        }

        return null;
    }
}
