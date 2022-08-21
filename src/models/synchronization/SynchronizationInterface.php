<?php


namespace wm\admin\models\synchronization;


interface SynchronizationInterface
{
    public static function getCountB24();

    public static function getB24Fields();

    public static function getB24FieldsList();

    public static function startSynchronization($period);

    public static function stopSynchronization();

    public static function addJobFull($method, $dateTimeStart = null);

    public function loadData($data);

    public static function createTable();

}