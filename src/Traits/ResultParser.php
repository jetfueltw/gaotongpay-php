<?php

namespace Jetfuel\Gaotongpay\Traits;
use Sunra\PhpSimple\HtmlDomParser;

trait ResultParser
{
    /**
     * Parse HTML format response to string.
     *
     * @param string $response
     * @return string|null
     */
    public function parseResponse($response)
    {
        $html = HtmlDomParser::str_get_html($response);

        $imgSrc = $html->find('img', 0);
        if (isset($imgSrc)) 
        {
            return ltrim($imgSrc->src,'/');
        }

        return null;
    }

    /**
     * Parse JSON format response to array.
     *
     * @param string $response
     * @return array
     */
    public function parseQueryResponse($response)
    {
        return json_decode($response, true);
    }
}
