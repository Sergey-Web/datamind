<?php

use app\services\Search\Clients\{DocumentClient, IndexClient};
use app\services\Search\ImportToElasticService;
use app\services\Search\IndexSetup;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'mongodb' => [
            'class' => \yii\mongodb\Connection::class,
            'dsn' => 'mongodb://mongodb:27017/datamind',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            Client::class => static function () {
                return ClientBuilder::create()
                    ->setHosts(['http://opensearch:9200'])
                    ->build();
            },
            IndexClient::class => static function ($container) {
                return new IndexClient($container->get(Client::class));
            },
            DocumentClient::class => static function ($container) {
                return new DocumentClient($container->get(Client::class));
            },
            IndexSetup::class => static function ($container) {
                return new IndexSetup($container->get(IndexClient::class));
            },
            ImportToElasticService::class => static function ($container) {
                return new ImportToElasticService(
                    Yii::$app->mongodb,
                    $container->get(DocumentClient::class)
                );
            },

        ],
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // configuration adjustments for 'dev' environment
    // requires version `2.1.21` of yii2-debug module
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
