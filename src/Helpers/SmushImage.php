<?php

namespace OffbeatWP\ReSmush\Helpers;

class SmushImage
{

    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    public static function smushImage($image)
    {
        $file = $image['file'];
        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];
        $output = new \CURLFile($file, $mime, $name);
        $data = [
            "files" => $output,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.resmush.it/?qlty=80');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = curl_error($ch);
        }
        curl_close($ch);

        $results = json_decode($result);

        $content = file_get_contents($results->dest);
        file_put_contents($file, $content);

        return $image;
    }
}