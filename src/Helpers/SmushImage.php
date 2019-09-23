<?php

namespace OffbeatWP\ReSmush\Helpers;

class SmushImage
{

    public function __construct($image)
    {
        $this->image = $image;
        $this->quality = 90;
        $this->url = 'http://api.resmush.it/?qlty=';
    }

    public function execute()
    {
        if ($this->hasAllowedType($this->image['type']) == true && $this->hasAllowedSize($this->image['file']) == true) {
            $request = $this->makeCurlRequest($this->image['file']);

            if ($request != false) {
                $this->pullImage($request, $this->image['file']);
            }
        }
    }

    public function hasAllowedType($image)
    {
        switch ($image) {
            case "image/jpeg":
                return true;
                break;
            case "image/png":
                return true;
                break;
            case "image/gif":
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    public function hasAllowedSize($image)
    {
        if (filesize($image) < 5242880) {
            return true;
        }

        return false;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    public function pullImage($result, $file)
    {
        $content = file_get_contents($result->dest);
        file_put_contents($file, $content);
    }

    public function makeCurlRequest($file)
    {
        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];
        $output = new \CURLFile($file, $mime, $name);

        $data = [
            "files" => $output,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . $this->quality);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log(curl_error($ch));

            return false;
        }

        curl_close($ch);

        if ($this->hasNoCurlError($result) != true) {
            return false;
        }

        return json_decode($result);
    }

    public function hasNoCurlError($result)
    {
        $checkError = json_decode($result);

        switch ($checkError->error) {
            case 301:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 400:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 402:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 403:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 404:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 501:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 502:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 503:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            case 504:
                error_log('error Log:' . $checkError->error_long);

                return false;
                break;
            default:
                error_log('error Log:' . $checkError->error_long);

                return true;
                break;
        }
    }

}