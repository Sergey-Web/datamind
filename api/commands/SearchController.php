<?php

declare(strict_types=1);

namespace app\commands;

use app\services\Search\Clients\IndexClient;
use app\services\Search\IndexSetup;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

final class SearchController extends Controller
{
    public function actionReindex(): void
    {
        /** @var IndexSetup $indexSetup */
        $indexSetup = Yii::$container->get(IndexSetup::class);
        $indexSetup->recreate();

              /** @var IndexClient $indexClient */
        $indexClient = Yii::$container->get(IndexClient::class);

        if ($indexClient->exists($indexSetup::INDEX_NAME)) {
            $this->stdout("Index created successfully\n", Console::FG_GREEN);
        } else {
            $this->stderr("Index NOT created\n", Console::FG_RED);
        }
    }
}