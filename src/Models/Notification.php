<?php


namespace Ivfuture\EventNotification\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'sender_id', 'receiver_id', 'notifiable_type', 'notifiable_id', 'title', 'description'];

    public static $rulesCreate = array(
        'type' => 'required',
        'sender_id' => 'required',
        'receiver_id' => 'required',
        'notifiable_type' => 'required',
        'notifiable_id' => 'required',
        'title' => 'required',
        'description' => 'required',

    );

    public function newCollection(array $models = [])
    {
        return new NotificationCollection($models);
    }

    public function saveNotification($sender_id, $receiver_id, $notifiable_type, $notifiable_id, $channel_id, $title, $description){

        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->notifiable_type = $notifiable_type;
        $this->notifiable_id = $notifiable_id;
        $this->channel_id = $channel_id;
        $this->title = $title;
        $this->description = $description;
        $this->save();

    }

    public function channel(){
        return $this->belongsTo(NotificationChannel::class,'channel_id');
    }

}
