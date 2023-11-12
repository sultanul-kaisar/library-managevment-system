<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Image;

trait Base
{
    /**
     * @param string $message
     * @param array<string, mixed> $data
     * @param string $code
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public static function error($message, $data = [], string $type = null)
    {
        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => $message,
            'data' => $data,
            'type' => $type
        ]);
    }

    /**
     * @param string $message
     * @param array<string, mixed> $data
     * @param string $code
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public static function success(string $message, $data = [],  string $type = null)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
            'type' => $type
        ]);
    }

    public static function validation($validator)
    {
        // if ($validator->fails()) return Base::error($validator->errors()->first(), $validator->errors());
        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => $validator->errors()->first(),
            'data' => $validator->errors(),
            'type' => 'validation'
        ]);
    }
    public static function validation_fail($validator)
    {
        // if ($validator->fails()) return Base::error($validator->errors()->first(), $validator->errors());
        // return response()->json([
        //     'success' => false,
        //     'code' => 400,
        //     'message' => $validator->errors()->first(),
        //     'data' => $validator->errors(),
        //     'type' => 'validation'
        // ]);

        return (object) collect([
            'success' => false,
            'code' => 400,
            'message' => $validator->errors()->first(),
            'data' => $validator->errors(),
            'type' => 'validation'
        ])->all();
    }
    public static function exception($e)
    {
        // return Base::error($e->getMessage()." ".$e->getFile()." ".$e->getLine());

        $exception_msg = $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();

        error_log("⚠️⚠️⚠️⚠️⚠️⚠️: " . $exception_msg);

        // $msg = 'An error occurred!!'; // ! For Production
        $msg = $exception_msg; // ! For Development
        // $msg = GlobalTrait::translate($msg); //! translated message

        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => $msg,
            'data' => $e,
            'type' => 'exception'
        ]);
    }

    public static function pass($msg = 'Task successful!!', $data = [], $type = null)
    {
        // $msg = GlobalTrait::translate($msg);
        return (object) collect([
            'success' => true,
            'message' => $msg,
            'data' => $data,
            'type' => $type
        ])->all();
    }

    public static function fail($msg = 'An error occurred!!', $error = [], $err_type = null)
    {
        // $msg = GlobalTrait::translate($msg);
        return (object) collect([
            'success' => false,
            'message' => $msg,
            'data' => $error,
            'type' => $err_type
        ])->all();
    }

    public static function exception_fail($e)
    {
        $exception_msg = $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();

        error_log("⚠️⚠️⚠️⚠️⚠️⚠️: " . $exception_msg);

        $msg = $exception_msg; // ! For Development
        // $msg = GlobalTrait::translate($msg); //! for translation

        return (object) collect([
            'success' => false,
            'code' => 400,
            'message' => $msg,
            'data' => $e,
            'type' => 'exception'
        ])->all();
    }

    public static function now()
    {
        return Carbon::now();
    }

    public static function carbonDateParse($date)
    {
        return Carbon::parse($date)->isoFormat('D MMM, YYYY');
    }

    public static function timeParse($time)
    {
        return Carbon::parse($time)->format('H:i');
    }
    public static function dateParse($date)
    {
        if ($date == null) return null;
        return Carbon::parse($date)->format('Y-m-d');
    }
    public static function formateUserName($name)
    {
        // substring to @ from email
        $name = explode('@', $name);

        return $name[0] ?? '';
    }

    public static function carbonParse($date)
    {
        return Carbon::parse($date);
    }

    public static function dateTimeConcat($date, $time)
    {

        return Carbon::parse($date . ' ' . $time);
    }

    public static function generateUDID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public static function imageUpload($image, $model)
    {
        $uuid = (string) Str::uuid();
        $image = $image; // image base64 encoded
        preg_match("/data:image\/(.*?);/",
            $image,
            $image_extension
        ); // extract the image extension
        $image = preg_replace('/data:image\/(.*?);base64,/', '',
            $image
        ); // remove the type part
        $image = str_replace(' ', '+', $image);
        $imageName = $model . '_' . time() . '_' . $uuid . '.' . $image_extension[1]; //generating unique file name;
        $file = base64_decode($image);

        $imageFile = Image::make($file);

        $imageFile->resize(720, 720, function ($constraint) {
            $constraint->aspectRatio();
        });

        // $imageFile->save('/images/' . $model . '/' . $imageName);

        $newPath = public_path() . '/images/' . $model;
        if (!file_exists($newPath)) {
            mkdir($newPath, 0755,true);
        }

        $imageFile->save(public_path() . '/images/' . $model . '/' . $imageName);

        return 'images/' . $model . '/' . $imageName;
    }

    public static function fileUpload($attachment, $name)
    {
        $result = "";
        $random = rand(1000, 9999);
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $name = str_replace(' ', '_', $name) . "_" . str_replace('-', '_', $date) . "_" . $random;

        $mime_type = substr($attachment, 5, strpos($attachment, ';') - 5);
        if (strpos($mime_type, 'pdf')) $extension = 'pdf';
        else if (strpos($mime_type, 'openxmlformats-officedocument.wordprocessingml.document')) $extension = 'docx';
        elseif (strpos($mime_type, 'msword')) $extension = 'docx';
        elseif (strpos($mime_type, 'zip')) $extension = 'zip';
        elseif (strpos($mime_type, 'image')) $extension = 'image';
        else $extension = 'not_pdf';

        if ($extension == "pdf") {
            $pdf = preg_replace('/data:application\/(.*?);base64,/', '', $attachment);
            $pdf = base64_decode($pdf);
            $path = 'library/pdf/' . $name . '.pdf';

            $newPath = public_path() . '/library/pdf/';
            if (!file_exists($newPath)) {
                mkdir($newPath, 0755, true);
            }
            // return 123;
            file_put_contents($newPath . '/' . $name . '.pdf', $pdf);

            $result = $path;
        } elseif ($extension == "docx") {
            $pdf = preg_replace('/data:application\/(.*?);base64,/', '', $attachment);
            $pdf = base64_decode($pdf);
            $path = 'library/application/' . $name . '.docx';

            $newPath = public_path() . '/library/application/';
            if (!file_exists($newPath)) {
                mkdir($newPath, 0755, true);
            }
            // return 123;
            file_put_contents($newPath . '/' . $name . '.docx', $pdf);

            $result = $path;
        } else {
            preg_match("/data:image\/(.*?);/", $attachment, $image_extension); // extract the image extension
            $image = preg_replace('/data:image\/(.*?);base64,/', '', $attachment); // remove the type part
            $image = str_replace(' ', '+', $image);
            $imageName = $name . '.' . $image_extension[1]; //generating unique file name;
            $file = base64_decode($image);

            $imageFile = Image::make($file);

            $newPath = public_path() . '/library/application';
            if (!file_exists($newPath)) {
                mkdir($newPath, 0755, true);
            }

            $imageFile->save(public_path() . '/library/application/' . $imageName);

            $result = 'library/application/' . $imageName;
        }

        return $result;
    }


    public static function excelUpload($attachment, $name)
    {
        $result = "";
        $random = rand(1000, 9999);
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $name = str_replace(' ', '_', $name) . "_" . str_replace('-', '_', $date) . "_" . $random;

        $excel = preg_replace('/data:application\/(.*?);base64,/', '', $attachment);
        $excel = base64_decode($excel);
        $path = 'library/excel/' . $name . '.xlsx';

        $newPath = public_path() . '/library/excel/';
        if (!file_exists($newPath)) {
            mkdir($newPath, 0755, true);
        }
        file_put_contents($newPath . '/' . $name . '.xlsx', $excel);

        $result = $path;

        return $result;
    }
}
