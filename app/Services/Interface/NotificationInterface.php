<?php

namespace App\Services\Interface;

use Illuminate\Http\Request;

interface NotificationInterface
{
   public function getNotification(Request $request);
   public function notificationDetails(Request $request);

   public function notificationMarkAllRead(Request $request);
   public function notificationMarkAsUnRead(Request $request);
   
   public function deleteNotification(Request $request);
   public function deleteAllNotification(Request $request);
   
}