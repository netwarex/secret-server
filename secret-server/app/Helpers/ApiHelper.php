<?php
namespace App\Helpers;

use Illuminate\Http\Request;

class ApiHelper
{

    static public $fallbackFormat = 'json';

    public static function getResponseFormatter()
    {
        $request = app('request');
        $format = "format" . ucfirst($request->query('format'));

        if(method_exists(get_called_class(), $format))
        {
            return $format;
        }
        else
        {
            return "format".ucfirst(self::$fallbackFormat);
        }
    }

    public static function formatXml($model, $status)
    {
        $xmlname = 'Response';
        if($status == 200) $xmlname = $model->xmlName;
        if(!is_array($model)) $model = $model->toArray();
        return response(XmlHelper::formatXml(XmlHelper::toXml($model, $xmlname)), $status)->header('Content-Type', 'application/xml');
    }

    public static function formatJson($model, $status)
    {
        return response()->json($model, $status);
    }

    public static function response($model, $status = 200)
    {
        $formatter = self::getResponseFormatter();
        return self::$formatter($model, $status);
    }

    public static function error($message, $code)
    {
        return self::response(['code' => $code, 'message' => $message], $code);
    }
}
