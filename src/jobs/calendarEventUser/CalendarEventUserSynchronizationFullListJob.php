<?php

namespace wm\admin\jobs\calendarEventUser;

use yii\queue\JobInterface;
use wm\admin\jobs\calendarEventUser\CalendarEventUserSynchronizationFullJob as FullJob;

class CalendarEventUserSynchronizationFullListJob extends FullJob implements JobInterface
{
}
