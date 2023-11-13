<?php

namespace App\Services\Repository;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Services\Interface\NotificationInterface;
use App\Traits\Base;
use Illuminate\Support\Facades\Validator;

class NotificationRepository implements NotificationInterface
{
   use Base;

   public function getNotification($request)
   {
       try {
           $take = $request->take ?? 20;
           $user_id = auth()->user()->id;
           $data = [];
           $notifications = Notification::where('to_id', $user_id)
                           ->orderBy('id', 'desc')
                           ->with('from_user:id,name')
                           ->with('to_user:id,name')
                           ->get();

           $unseen_count = Notification::where('to_id', $user_id)->where('is_seen', false)->get()->count();

           if ($notifications->count() > 0) {
               foreach ($notifications as $notification) {
                  $data[] = [
                     'id' => $notification->id,
                     'title' => __($notification->title),
                     'type' => $notification->type,
                     'description' => __($notification->description),
                     'is_seen' => $notification->is_seen,
                     'created_at' => $notification->created_at,
                  ];                   
               }
           }
           $notifications = collect($data)->paginate($take);
           $notification = NotificationResource::collection($notifications)->response()->getData(true);
           $data = [
               'unseen_count' => $unseen_count,
               'notifications' => $notification,
           ];

           return Base::pass('All Notification', $data);
       } catch (\Exception $e) {
           return Base::exception_fail($e);
       }
   }

   public function notificationDetails($request)
   {
       try {
           $validator = Validator::make($request->all(), [
               'id' => 'required',
           ]);

           if ($validator->fails())
               return Base::fail($validator->errors()->first(), $validator->errors());

           $user_id = auth()->user()->id;

           $notification = Notification::where('id', $request->id)->first();
           if ($notification == null) return Base::fail("Not Found", $notification);

           if ($notification->to_id != $user_id) return Base::error('Notification user does not match');

           if ($notification->is_seen == 0) {
               $notification->is_seen = 1;
               $notification->save();
           }

           $item = [
               'id' => $notification->id,
               'title' => $notification->title,
               'type' => $notification->type,
               'description' => $notification->description,
               'is_seen' => $notification->is_seen,
               'created_at' => $notification->created_at,
           ];

           return Base::pass('Notification Found!', $item);
       } catch (\Exception $e) {
           return Base::exception_fail($e);
       }
   }

   public function notificationMarkAllRead($request)
   {
       try {
           // user all unread notifications marked as read
           $user_id = auth()->user()->id;
           $notification = Notification::where('to_id', $user_id)->update(['is_seen' => true]);

           if (!isset($notification)) return Base::fail('Notification not found!');

           return Base::pass('Notification All Marked Read!', $notification);
       } catch (\Exception $e) {
           return Base::exception_fail($e);
       }
   }

   public function notificationMarkAsUnRead($request)
   {
       try {
           $user_id = auth()->user()->id;
           $notification = Notification::where('to_id', $user_id)->update(['is_seen' => false]);

           return Base::pass('Notification All Marked Unread!', $notification);
       } catch (\Exception $e) {
           return Base::exception_fail($e);
       }
   }

   public function deleteNotification($request)
   {
       try {
           $notification = Notification::find($request->id);
           if (!isset($notification)) return Base::fail('Notification not found!');

           $notification->delete();

           return Base::pass('Notification Deleted!');
       } catch (\Exception $e) {
           return Base::exception_fail($e);
       }
   }

   public function deleteAllNotification($request)
   {
       try {
           $user_id = auth()->user()->id;
           $notification = Notification::where('to_id', $user_id)->delete();

           return Base::pass('All Notification Deleted!');
       } catch (\Exception $e) {
           return Base::exception_fail($e);
       }
   }
}