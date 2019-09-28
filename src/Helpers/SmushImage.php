<?php

namespace OffbeatWP\ReSmush\Helpers;

use \Exception;

class SmushImage
{

    public function __construct($imageType, $imageFile)
    {
        $this->image->type = $imageType;
        $this->image->file = $imageFile;
        $this->url = 'http://api.resmush.it/?qlty=';
        $this->exif = true;
        $this->client = new GuzzleHttp\Client();
    }

    public function execute()
    {
        if (General::hasAllowedType($this->image->type) == true && General::hasAllowedSize($this->image->file) == true) {
            $request = $this->makeCurlRequest($this->image->file);

            if ($request != false) {
                $this->pullImage($request, $this->image->file);
            }
        }
    }

    public function setExif($exif)
    {
        if ($exif == true) {
            $this->exif = 'true';
        } else {
            $this->exif = 'false';
        }
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
        // $output = new \CURLFile($file, $mime, $name);

        // $data = [
        //     "files" => $output,
        // ];

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url . $this->quality . '&exif=' . $this->exif);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // $result = curl_exec($ch);

        // if (curl_errno($ch)) {
        //     error_log(curl_error($ch));

        //     return false;
        // }

        // curl_close($ch);

        // try {
        //     $this->hasNoCurlError($result);
        // } catch (Exception $e) {
        //     error_log($e->getMessage());

        //     return false;
        // }
        
        $result = $this->client->request('GET', $this->url . $this->quality . '&exif=' . $this->exif);

        if ($result->getStatusCode() != 200){
            error_log('Api error:' . $result->getStatusCode());
            return false;
        }

        return json_decode($result);
    }

    

}
