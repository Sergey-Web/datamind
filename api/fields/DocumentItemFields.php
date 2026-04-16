<?php

declare(strict_types=1);

namespace app\fields;

final class DocumentItemFields
{
    public const string FIRM = 'firm';
    public const string REGION = 'region';
    public const string CITY = 'city';
    public const string INVOICE_DATE = 'invoice_date';

    public const string DELIVERY_ADDRESS = 'delivery_address';
    public const string CLIENT_ADDRESS = 'client_address';

    public const string CLIENT_NAME = 'client_name';
    public const string CLIENT_CODE = 'client_code';
    public const string CLIENT_DIVISION_CODE = 'client_division_code';
    public const string CLIENT_OKPO = 'client_okpo';

    public const string LICENSE = 'license';
    public const string LICENSE_EXPIRE_DATE = 'license_expire_date';

    public const string PRODUCT_CODE = 'product_code';
    public const string BARCODE = 'barcode';
    public const string PRODUCT_NAME = 'product_name';
    public const string MORION_CODE = 'morion_code';

    public const string UNIT = 'unit';
    public const string MANUFACTURER = 'manufacturer';
    public const string SUPPLIER = 'supplier';

    public const string QUANTITY = 'quantity';
    public const string WAREHOUSE = 'warehouse';

    public const array ALL = [
        self::FIRM,
        self::REGION,
        self::CITY,
        self::INVOICE_DATE,

        self::DELIVERY_ADDRESS,
        self::CLIENT_ADDRESS,

        self::CLIENT_NAME,
        self::CLIENT_CODE,
        self::CLIENT_DIVISION_CODE,
        self::CLIENT_OKPO,

        self::LICENSE,
        self::LICENSE_EXPIRE_DATE,

        self::PRODUCT_CODE,
        self::BARCODE,
        self::PRODUCT_NAME,
        self::MORION_CODE,

        self::UNIT,
        self::MANUFACTURER,
        self::SUPPLIER,

        self::QUANTITY,
        self::WAREHOUSE,
    ];
}
