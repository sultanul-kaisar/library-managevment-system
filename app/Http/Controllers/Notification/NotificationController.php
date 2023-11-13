<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\Interface\NotificationInterface;
use App\Traits\Base;
use Exception;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use Base;

    private $notificationInterface;

    public function __construct(NotificationInterface $notificationInterface)
    {
        $this->notificationInterface = $notificationInterface;
    }


    public function getNotification(Request $request)
    {
        try {
            $data = $this->notificationInterface->getNotification($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function notificationDetails(Request $request)
    {
        try {
            $data = $this->notificationInterface->notificationDetails($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function notificationMarkAllRead(Request $request)
    {
        try {
            $data = $this->notificationInterface->notificationMarkAllRead($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function notificationMarkAsUnRead(Request $request)
    {
        try {
            $data = $this->notificationInterface->notificationMarkAsUnRead($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function deleteNotification(Request $request)
    {
        try {
            $data = $this->notificationInterface->deleteNotification($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function deleteAllNotification(Request $request)
    {
        try {
            $data = $this->notificationInterface->deleteAllNotification($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
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
