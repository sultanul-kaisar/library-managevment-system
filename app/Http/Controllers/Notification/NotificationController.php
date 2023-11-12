<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\Interface\NotificationInterface;
use App\Traits\Base;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use Base;

    private $notificationInterface;

    public function __construct(NotificationInterface $notificationInterface)
    {
        $this->notificationInterface = $notificationInterface;
    }

    public static function setNotification($data)
    {
        $to_id = $data['to_id'];
        $from_id = $data['from_id'];
        $type = $data['type'];
        $title_key = $data["title"];
        $description_key = $data['description'];

        $user = User::find($to_id);

        if ($user != null) {
            $new_notification = new Notification();
            $new_notification->to_id = $to_id;
            $new_notification->from_id = $from_id;
            $new_notification->type = $type;
            $new_notification->title = $title_key;
            $new_notification->description = $description_key;
            $new_notification->save();

            $title = __($title_key);
            $body = __($description_key);

            $notification = array(
                "title" => $title,
                "body" =>  $body
            );

            return Base::success('New notification has been save', $notification);

        } else {
            return Base::error('User not found');
        }
    }


}
