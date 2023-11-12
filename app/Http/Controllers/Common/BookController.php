<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Books\AddBookRequest;
use App\Services\Interface\BookInterface;
use App\Traits\Base;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use Base;
    private $bookInterface;

    public function __construct(BookInterface $bookInterface)
    {
        $this->bookInterface = $bookInterface;
    }

    public function bookList(Request $request)
    {
        try {
            $data = $this->bookInterface->bookList($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function bookAdd(AddBookRequest $request)
    {
        try {
            $data = $this->bookInterface->bookAdd($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function bookUpdate(Request $request)
    {
        try {
            $data = $this->bookInterface->bookUpdate($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function bookDelete(Request $request)
    {
        try {
            $data = $this->bookInterface->bookDelete($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }    
    }

    public function bookStatus(Request $request)
    {
        try {
            $data = $this->bookInterface->bookStatus($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function bookRestore(Request $request)
    {
        try {
            $data = $this->bookInterface->bookRestore($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function booksList(Request $request)
    {
        try {
            $data = $this->bookInterface->booksList($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function bookView(Request $request)
    {
        try {
            $data = $this->bookInterface->bookView($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }


}

