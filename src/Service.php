<?php

namespace OffbeatWP\ReSmush;

use OffbeatWP\Services\AbstractService;
use OffbeatWP\ReSmush\Helpers\SmushImage;

class Service extends AbstractService
{

    public function register()
    {
//        add_filter('wp_handle_upload', [$this, 'handleUpload'], 10, 2);

        add_filter('wp_generate_attachment_metadata', [$this, 'handleThumbnails'], 10, 2);
    }

    public function handleUpload($image)
    {
        $apiCall = new SmushImage($image['type'], $image['file']);
        $apiCall->quality = 90;
        $apiCall->execute();

        return $image;
    }

    public function handleThumbnails($image, $key)
    {

        $basePath = substr($image["file"], 0, strrpos($image["file"], '/'));
        $file = wp_upload_dir()['basedir'] . '/' . $basePath . '/' . $image["sizes"]["thumbnail"]["file"];
        $apiCall = new SmushImage(get_post_mime_type($key) , $file);
        $apiCall->quality = 90;
        $apiCall->execute();

        return $image;
    }

}