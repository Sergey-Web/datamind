<?php

declare(strict_types=1);

namespace app\commands;

use app\services\Search\Clients\IndexClient;
use Yii;
use yii\console\Controller;

final class SearchController extends Controller
{
    public function actionReindex(): void
    {
        $service = Yii::$container->get(IndexClient::class);
        $service->recreate();

        $this->stdout("Done\n");
    }
}