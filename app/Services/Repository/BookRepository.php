<?php

namespace App\Services\Repository;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\Interface\BookInterface;
use App\Traits\Base;
use Exception;
use Illuminate\Support\Facades\Validator;

class BookRepository implements BookInterface
{
   use Base;
   public function bookList($request)
   {
      try {
         $take = $request['take'] ?? 20;

         $books = Book::orderBy('created_at', 'desc')->get();
         $books_all = collect($books)->paginate($take);
         
         $data = BookResource::collection($books_all)->response()->getData(true);

         return Base::pass('All Books List', $data);

      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function bookAdd($request)
   {
      try {
         $book = new Book();
         $book->title = $request->title;
         $book->author = $request->author;
         $book->description = $request->description;
         $book->qty = $request->qty;

         if (isset($request->image)) {

            if ($book->image) {
                if (file_exists(public_path() . $book->image)) {
                    unlink(public_path() . '/' . $book->image);
                }
            }
            $book->image = Base::imageUpload($request->image, 'books');
         }
        $book->save();
        $book->refresh();

        return Base::pass('New Book Added Successfully', $book);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function bookUpdate($request)
   {
      try {
         $book = Book::find($request->id);
         if (!isset($book)) return Base::fail('Book not found!');

         Validator::make($request->all(), [
            'title' => 'unique:books,title,' . $book->id,
         ]);
         
         $book->title = isset($request->title) ? $request->title : $book->title;
         $book->author = isset($request->author) ? $request->author : $book->author;
         $book->description = isset($request->description) ? $request->description : $book->description;
         $book->qty = isset($request->qty) ? $request->qty : $book->qty;

         if (isset($request->image)) {

            if ($book->image) {
                if (file_exists(public_path() . $book->image)) {
                    unlink(public_path() . '/' . $book->image);
                }
            }
            $book->image = Base::imageUpload($request->image, 'books');
         }

         $book->save();
         $book->refresh();

        return Base::pass('Book Updated Successfully', $book);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function bookDelete($request)
   {
      try {
         $book = Book::find($request->id);
         if (!isset($book)) return Base::fail('Book not found!');
         $book->delete();

         return Base::pass('Book moved to trash');
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function bookStatus($request)
   {
      try {
         $book = Book::find($request->id);
         if (!isset($book)) return Base::fail('Book not found!');
         $book->is_active = !$book->is_active;
         $book->save();
         
         return Base::pass('Book Status Updated Successfully', $book);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function bookRestore($request)
   {
      try {
         $book = Book::withTrashed()->find($request->id);

         if (!$book) {
            return Base::fail('Book not found!');
         }

         if ($book->trashed()) {
            $book->restore();
            return Base::pass('Book Restored Successfully', $book);
         }

         return Base::fail('Book is not in trash!');
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function booksList($request)
   {
      try {
         $take = $request['take'] ?? 20;

         $books = Book::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();
         $book_all = collect($books)->paginate($take);
         $data = BookResource::collection($book_all)->response()->getData(true);
         return Base::pass('All Books List', $data);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function bookView($request)
   {
      try {
         $book = Book::where('is_active', 1)->find($request->id);
         if (!isset($book)) return Base::fail('Book not found!');
         $data = new BookResource($book);
         return Base::pass('Book Details', $data);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }
   


}