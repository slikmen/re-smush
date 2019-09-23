<?php

namespace OffbeatWP\ReSmush;

use OffbeatWP\Services\AbstractService;
use OffbeatWP\ReSmush\Helpers\SmushImage;

class Service extends AbstractService
{

    public function register()
    {
        add_filter('wp_handle_upload', [$this, 'handleImage'], 10, 2);
    }

    public function handleImage($image)
    {
        $Api = new SmushImage($image);
        $Api->execute();

        return $image;
    }

}