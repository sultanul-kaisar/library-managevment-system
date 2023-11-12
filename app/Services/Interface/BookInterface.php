<?php

namespace App\Services\Interface;

use Illuminate\Http\Request;

interface BookInterface
{
   public function bookList(Request $request);
   public function bookAdd(Request $request);
   public function bookUpdate(Request $request);
   public function bookDelete(Request $request);
   public function bookStatus(Request $request);
   public function bookRestore(Request $request);
   public function booksList(Request $request);
   public function bookView(Request $request);

}