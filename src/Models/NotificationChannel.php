<?php

namespace Ivfuture\EventNotification\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationChannel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notification_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['label', 'icon'];

    public static $rulesCreate = array(
        'label' => 'required',
        'icon' => 'required'

    );

    public function notifications(){
        return $this->hasMany(Notification::class, 'channel_id');
    }


}
