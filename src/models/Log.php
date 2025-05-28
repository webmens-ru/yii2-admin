<?php

namespace wm\admin\models;

use Exception;
use wm\yii\helpers\ArrayHelper;
use Yii;
use yii\base\BaseObject;
use DateTime;
use DateInterval;
use yii\base\ErrorException;
use yii\helpers\FileHelper;


class Log extends BaseObject
{
    /**
     * @param mixed[] $params
     * @return void
     * @throws ErrorException
     * @throws Exception
     */
    public static function clearOldLogs(array $params = []){
        $period = 'P14D';
        if(ArrayHelper::getValue($params, 'period')){
            $period = ArrayHelper::getValue($params, 'period');
        }


        $logPath = \Yii::getAlias('@app') . '/log'; // Путь к папке log

        if (!is_dir($logPath)) {
            Yii::warning("Папка логов не найдена: " . $logPath, 'clearOldLogs');
            return;
        }

        $directories = glob($logPath . '/*', GLOB_ONLYDIR);
        if (empty($directories)) {
            Yii::warning("Нет папок для удаления.", 'clearOldLogs');
            return;
        }

        $cutoffDate = new DateTime();
        $cutoffDate->sub(new DateInterval($period)); // 14 дней назад
        $format = 'Y_m_d';

        foreach ($directories as $dir) {
            $dirName = basename($dir);
            $dirDate = DateTime::createFromFormat($format, $dirName);
            if ($dirDate && $dirDate < $cutoffDate) {
                FileHelper::removeDirectory($dir);
                Yii::warning("Удалена папка: " . $dirName, 'clearOldLogs');
            }
        }
    }

}
