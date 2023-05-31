<?php

namespace wm\admin\jobs\taskElapsedItem;

use yii\queue\JobInterface;
use wm\admin\jobs\taskElapsedItem\TaskElapsedItemSynchronizationFullJob as FullJob;

class TaskElapsedItemSynchronizationFullGetJob extends FullJob implements JobInterface
{
}
