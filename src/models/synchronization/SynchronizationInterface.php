<?php

namespace wm\admin\models\synchronization;

use wm\admin\models\settings\Agents;

/**
 *
 */
interface SynchronizationInterface
{
    /**
     * @return mixed
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function getCountB24();

    /**
     * @return mixed[]
     */
    public static function getB24Fields();

    /**
     * @return mixed[]
     * @throws \Exception
     */
    public static function getB24FieldsList();

    /**
     * @param Agents $modelAgentTimeSettings
     * @return void
     */
    public static function startSynchronization(Agents $modelAgentTimeSettings);

    /**
     * @return void
     */
    public static function stopSynchronization();


    /**
     * @param string $method
     * @param string|null $dateTimeStart
     * @return mixed
     */
    public static function addJobFull($method, $dateTimeStart = null);

    /**
     * @param mixed[] $data
     * @return void
     */
    public function loadData($data);

    /**
     * @param string $synchronizationEntityId
     * @return mixed
     */
    public static function createTable($synchronizationEntityId);
}
