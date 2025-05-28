<?php

namespace wm\admin\models;

use Yii;
use yii\base\BaseObject;
use DateTime;
use DateInterval;
use yii\helpers\FileHelper;


class Log extends BaseObject
{
    /**
     * @return void
     * @throws \yii\base\ErrorException
     */
    public static function clearOldLogs(){
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
        $cutoffDate->sub(new DateInterval('P14D')); // 14 дней назад
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
