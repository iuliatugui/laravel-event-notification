<?php


namespace Ivfuture\EventNotification\Models;

use Illuminate\Database\Eloquent\Collection;
use function foo\func;

class NotificationCollection extends Collection
{
    /**
     * Mapping the array's results according to the time they were sent
     *
     * @return NotificationCollection
     */
    public function groupByDate(){

        return $this->groupBy(function($notification){

            if($notification->created_at->isToday()) {
                return __('today');
            }
            if($notification->created_at->isCurrentWeek()) {
                return __('this week');
            }
            if($notification->created_at->isLastWeek()) {
                return __('last week');
            }
            return __('older');

        });
    }
}
