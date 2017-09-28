<?php
namespace App\Helpers;

class XmlHelper
{

    public static function toXml(array $vars, $xmlname = 'response', $xml = null)
    {
        if (is_null($xml))
        {
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><' . $xmlname . '/>');
        }

        foreach ($vars as $key => $value)
        {
            if (is_array($value))
            {
                self::toXml($value, null, $xml->addChild($key));
            }
            else
            {
                $xml->addChild($key, $value);
            }
        }

       return $xml->asXML();
    }

    public static function formatXml($xml)
    {
        $dom = new \DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        return $dom->saveXML();
   }
}
