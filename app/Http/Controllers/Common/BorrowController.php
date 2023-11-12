<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Services\Interface\BorrowInterface;
use App\Traits\Base;
use Exception;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    use Base;
    private $borrowInterface;

    public function __construct(BorrowInterface $borrowInterface)
    {
        $this->borrowInterface = $borrowInterface;
    }

    public function borrowRequestList(Request $request)
    {
        try {
            $data = $this->borrowInterface->borrowRequestList($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }

    public function borrowRequest(Request $request)
    {
        try {
            $data = $this->borrowInterface->borrowRequest($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
            
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function borrowRequestUpdate(Request $request)
    {
        try {
            $data = $this->borrowInterface->borrowRequestUpdate($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function borrowRequestDelete(Request $request)
    {
        try {
            $data = $this->borrowInterface->borrowRequestDelete($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function requestList(Request $request)
    {
        try {
            $data = $this->borrowInterface->requestList($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }    
    }

    public function requestStatus(Request $request)
    {
        try {
            $data = $this->borrowInterface->requestStatus($request);
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
}
