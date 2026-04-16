<?php

declare(strict_types=1);

namespace app\mappers;

use app\fields\DocumentItemFields;
use DateTimeImmutable;

final class DocumentItemMapper
{
private const HEADER_MAP = [
    'фирма' => DocumentItemFields::FIRM,
    'область' => DocumentItemFields::REGION,
    'город' => DocumentItemFields::CITY,
    'дата_накл' => DocumentItemFields::INVOICE_DATE,

    'факт_адрес_доставки' => DocumentItemFields::DELIVERY_ADDRESS,
    'юр_адрес_клиента' => DocumentItemFields::CLIENT_ADDRESS,

    'клиент' => DocumentItemFields::CLIENT_NAME,
    'код_клиента' => DocumentItemFields::CLIENT_CODE,
    'код_подразд_кл' => DocumentItemFields::CLIENT_DIVISION_CODE,
    'окпо_клиента' => DocumentItemFields::CLIENT_OKPO,

    'лицензия' => DocumentItemFields::LICENSE,
    'дата_окончания_лицензии' => DocumentItemFields::LICENSE_EXPIRE_DATE,

    'код_товара' => DocumentItemFields::PRODUCT_CODE,
    'штрих-код_товара' => DocumentItemFields::BARCODE,
    'товар' => DocumentItemFields::PRODUCT_NAME,
    'код_мориона' => DocumentItemFields::MORION_CODE,

    'еи' => DocumentItemFields::UNIT,
    'производитель' => DocumentItemFields::MANUFACTURER,
    'поставщик' => DocumentItemFields::SUPPLIER,

    'количество' => DocumentItemFields::QUANTITY,
    'склад_филиал' => DocumentItemFields::WAREHOUSE,
];

    public function map(array $row): array
    {
        $result = [];

        foreach (self::HEADER_MAP as $csvKey => $field) {
            if (!in_array($field, DocumentItemFields::ALL, true)) {
                continue;
            }

            $value = $row[$csvKey] ?? null;

            $result[$field] = $this->castValue($field, $value);
        }

        return $result;
    }

    private function castValue(string $field, mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return match ($field) {
            DocumentItemFields::CLIENT_CODE,
            DocumentItemFields::CLIENT_DIVISION_CODE,
            DocumentItemFields::CLIENT_OKPO,
            DocumentItemFields::PRODUCT_CODE,
            DocumentItemFields::MORION_CODE,
            DocumentItemFields::QUANTITY => (int) $value,

            DocumentItemFields::INVOICE_DATE,
            DocumentItemFields::LICENSE_EXPIRE_DATE => $this->parseDate($value),

            default => $value,
        };
    }

    private function parseDate(string $date): ?string
    {
        $dateTime = DateTimeImmutable::createFromFormat('m/d/Y', $date);

        if ($dateTime === false) {
            return null;
        }

        return $dateTime->format(DATE_ATOM);
    }
}
