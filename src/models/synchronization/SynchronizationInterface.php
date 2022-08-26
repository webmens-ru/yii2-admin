<?php


namespace wm\admin\models\synchronization;


use wm\admin\models\settings\Agents;

interface SynchronizationInterface
{
    public static function getCountB24();

    public static function getB24Fields();

    public static function getB24FieldsList();

    public static function startSynchronization(Agents $modelAgentTimeSettings);

    public static function stopSynchronization();

    public static function addJobFull($method, $dateTimeStart = null);

    public function loadData($data);

    public static function createTable();

}