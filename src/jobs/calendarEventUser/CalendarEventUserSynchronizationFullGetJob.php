<?php

namespace wm\admin\jobs\calendarEventUser;

use yii\queue\JobInterface;
use wm\admin\jobs\calendarEventUser\CalendarEventUserSynchronizationFullJob as FullJob;

/**
 *
 */
class CalendarEventUserSynchronizationFullGetJob extends FullJob implements JobInterface
{
}
