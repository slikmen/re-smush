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

        $this->defaultQuality = 92;
    }

    public function handleUpload($image)
    {
        $apiCall = new SmushImage($image['type'], $image['file']);
        $apiCall->quality = $this->defaultQuality;
        $apiCall->execute();

        return $image;
    }

    public function handleThumbnails($image, $key)
    {

        $this->smushDemention($image, $key, 'thumbnail');
        $this->smushDemention($image, $key, 'medium_large');
        $this->smushDemention($image, $key, 'medium');
        $this->smushDemention($image, $key, 'large');

        return $image;
    }

    public function smushDemention($image, $key, $size)
    {
        $apiCall = new SmushImage(get_post_mime_type($key), $this->getFile($image, $size));
        $apiCall->quality = $this->defaultQuality;
        $apiCall->execute();
    }

    public function getBasePath($image)
    {
        return substr($image["file"], 0, strrpos($image["file"], '/'));
    }

    public function getFile($image, $size = 'thumbnail')
    {
        return wp_upload_dir()['basedir'] . '/' . $this->getBasePath($image) . '/' . $image["sizes"][$size]["file"];
    }

}