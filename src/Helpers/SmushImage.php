<?php

namespace OffbeatWP\ReSmush\Helpers;

class SmushImage
{

    public function __construct($image)
    {
        $this->image = $image;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    public function execute()
    {
        $file = $this->image['file'];
        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];
        $output = new \CURLFile($file, $mime, $name);
        $data = [
            "files" => $output,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.resmush.it/?qlty=90');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result);

        $this->pullImage($result, $file);

        return $this->image;
    }

    public function pullImage($result, $file)
    {
        $content = file_get_contents($result->dest);
//        error_log($content);
        file_put_contents($file, $content);
    }
}