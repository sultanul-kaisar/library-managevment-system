<?php

namespace App\Services\Interface;

interface BorrowInterface
{
   public function borrowRequestList($request);
   public function borrowRequest($request);
   public function borrowRequestUpdate($request);
   public function borrowRequestDelete($request);

   public function requestList($request);
   public function requestStatus($request);
   public function borrowRequestSearch($request);
}