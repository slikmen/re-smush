<?php

namespace OffbeatWP\ReSmush;

use OffbeatWP\Services\AbstractService;
use OffbeatWP\ReSmush\Helpers\SmushApi;

class Service extends AbstractService
{

    public function register()
    {
        add_filter('wp_handle_upload', [$this, 'SmushImages'], 10, 2);
    }

    public function smushImage($image)
    {
        return SmushApi::smushImage($image);
    }

}