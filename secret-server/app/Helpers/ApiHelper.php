<?php
namespace App\Helpers;

use Illuminate\Http\Request;

class ApiHelper
{

    public static function response($vars, $xmlname = 'response', $status = 200)
    {
        $request = app('request');
        $format = $request->query('format');

        switch($format)
        {
            case 'xml':
                if(!is_array($vars)) $vars = $vars->toArray();
                return response(XmlHelper::formatXml(XmlHelper::toXml($vars, $xmlname)), $status)->header('Content-Type', 'application/xml');
            break;

            default:
                return response()->json($vars, $status);
            break;
        }
    }

    public static function error($message, $code)
    {
        return self::response(['code' => $code, 'message' => $message], 'Error', $code);
    }
}
