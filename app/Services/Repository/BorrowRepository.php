<?php

namespace App\Services\Repository;

use App\Http\Controllers\Notification\NotificationController;
use App\Http\Resources\BorrowResource;
use App\Models\Book;
use App\Services\Interface\BorrowInterface;
use App\Models\Borrow;
use App\Models\BorrowHistory;
use App\Models\User;
use App\Traits\Base;
use Exception;
use Illuminate\Support\Facades\Auth;

class BorrowRepository implements BorrowInterface
{
   use Base;

   public function borrowRequestList($request)
   {
      try {
         $borrow_list = Borrow::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
         $data = BorrowResource::collection($borrow_list)->response()->getData(true);

         return Base::pass('All Borrow Request List', $data);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function borrowRequest($request)
   {
      try {
         $book = Book::find($request->book_id);
         if (!$book) {
            return Base::fail('Book Not Found');
         }

         if ($book->qty < 1) {
            return Base::fail('The Bokk you requested is out of stock');
         }

         if ($request->qty > $book->qty) {
            return Base::fail('The quantity you requested is not available');
         }

         $book->qty -= $request->qty;
         $book->save();

         $borrow = new Borrow();
         $borrow->user_id = Auth::user()->id;
         $borrow->book_id = $request->book_id;
         $borrow->borrow_code = random_int(100000, 999999);
         $borrow->message = $request->message;
         $borrow->qty = $request->qty;
         $borrow->borrow_date = Base::now();
         $borrow->save();
         $borrow->refresh();

         $admin = User::where('role', 'admin')->first();

         $notifications = [
            'title' => "Book Borrow Request",
            'description' => "Hello, " . $admin->name . "," . $borrow->user->name . " " . $borrow->book->title . " book borrow request has been created.",
            'to_id' => Auth::user()->id,
            'from_id' => Auth::user()->id,
            "type" => 'customer_admin'
         ];

         if (isset($notifications)) {
            NotificationController::setNotification($notifications);
         }

         return Base::pass('New Borrow Request Added Successfully', $borrow);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function borrowRequestUpdate($request)
   {
      try {
         $borrow = Borrow::where('status', 'pending')->find($request->id);
         if (!$borrow) {
            return Base::fail('Borrow Request Not Found');
         }

         $borrowHistory = new BorrowHistory();
         $borrowHistory->borrow_id = $borrow->id;
         $borrowHistory->previous_qty = $borrow->qty;
         $borrowHistory->updated_qty = $request->qty ?? $borrow->qty;
         $borrowHistory->previous_message = $borrow->message;
         $borrowHistory->updated_message = $request->message ?? $borrow->message;
         $borrowHistory->save();

         $book = Book::find($borrow->book_id);
         if ($book->qty < 1) {
            return Base::fail('The Bokk you requested is out of stock');
         }

         if ($request->qty > $book->qty) {
            return Base::fail('The quantity you requested is not available');
         }

         if ($borrowHistory->previous_qty != $borrowHistory->updated_qty) {
            $book->qty -= $borrowHistory->updated_qty - $borrowHistory->previous_qty;
            $book->save();
         }
         $borrow->qty = isset($request->qty) ? $request->qty : $borrow->qty;
         $borrow->message = isset($request->message) ? $request->message : $borrow->message;
         $borrow->save();
         $borrow->refresh();

         $admin = User::where('role', 'admin')->first();

         $notifications = [
            'title' => "Book Borrow Request Updated",
            'description' => "Hello, " . $admin->name . "," . $borrow->user->name . " " . $borrow->book->title . " book borrow request has been updated.",
            'to_id' => Auth::user()->id,
            'from_id' => Auth::user()->id,
            "type" => 'customer_admin'
         ];

         if (isset($notifications)) {
            NotificationController::setNotification($notifications);
         }

         return Base::pass('Borrow Request Updated Successfully', $borrow);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function borrowRequestDelete($request)
   {
      try {
         $borrow = Borrow::where('status', 'pending')->find($request->id);
         if (!$borrow) {
            return Base::fail('Borrow Request Not Found');
         }

         $borrow->delete();
         return Base::pass('Borrow Request Deleted Successfully');         
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function requestList($request)
   {
      try {
         $borrow_list = Borrow::orderBy('created_at', 'desc')
                        ->get();
         $data = BorrowResource::collection($borrow_list)->response()->getData(true);

         return Base::pass('All Borrow Request List', $data);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function requestStatus($request)
   {
      try {
         $borrow = Borrow::find($request->id);
         if (!$borrow) {
            return Base::fail('Borrow Request Not Found');
         }
         $borrow->status = $request->status;
         $borrow->return_date = Base::now()->addDays($request->return_date) ?? null;
         $borrow->save();
         $borrow->refresh();

         $book = Book::find($borrow->book_id);

         if ($borrow->status == 'rejected'|| $borrow->status == 'returned') {
            $book->qty += $borrow->qty;
            $book->save();
         }

         $user = User::where('id', $borrow->user_id)->first();
            if (!isset($user)) return Base::fail('User not found!');
            if ($request->status == 'accepted') {
               $notifications = [
                     'title' => "Book Borrow Request Accepted",
                     'description' => "Hello, " . $user->name . ", Your " . $borrow->book->title . " book borrow request has been accepted. Please collect your book from library and return the book within " . $request->return_date . " days. Thank you!",
                     'to_id' => $user->id,
                     'from_id' => Auth::user()->id,
                     "type" => 'admin_customer'
               ];
            } elseif ($request->status == 'rejected') {

               $notifications = [
                  'title' => "Book Borrow Request Rejected",
                  'description' => "Hello, " . $user->name . ", Your " . $borrow->book->title . " book borrow request has been rejected. Please contact library adminastration for further details",
                  'to_id' => $user->id,
                  'from_id' => Auth::user()->id,
                  "type" => 'admin_customer'
               ];
            } elseif ($request->status == 'returned') {

               $notifications = [
                  'title' => "Borrow Book Returned",
                  'description' => "Hello, " . $user->name . ", Your " . $borrow->book->title . " book has been returned. Thank you!",
                  'to_id' => $user->id,
                  'from_id' => Auth::user()->id,
                  "type" => 'admin_customer'
               ];
            }

            if (isset($notifications)) {
               NotificationController::setNotification($notifications);
            }
         return Base::pass('Borrow Request Status Updated Successfully', $borrow);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function borrowRequestSearch($request)
   {
      try {
         if ($request['search'] !== null) {
            $search = isset($request['search']) ? $request['search'] : null;
            $book_requests = Borrow::where('borrow_code', 'like', '%' . $search . '%')->get();
         }

        if ($request['status'] !== null) {
         $status = isset($request['status']) ? $request['status'] : null;
         $book_requests = Borrow::where('status', 'like', '%' . $status . '%')->get();
         }

         if (!isset($book_requests)) return Base::fail('No search result found!');
         $data = BorrowResource::collection($book_requests)->response()->getData(true);

         return Base::pass('Borrow Requests', $data);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }
}
