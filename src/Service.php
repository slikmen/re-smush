<?php

namespace OffbeatWP\ReSmush;

use OffbeatWP\Services\AbstractService;
use OffbeatWP\ReSmush\Helpers\SmushImage;

class Service extends AbstractService
{

    public function register()
    {
        add_filter('wp_handle_upload', [$this, 'handleUpload'], 10, 2);
    }

    public function handleUpload($image)
    {
        $apiCall = new SmushImage($image);
        $apiCall->execute();

        return $image;
    }

}