<?php

namespace OffbeatWP\ReSmush;

use OffbeatWP\Services\AbstractService;
use OffbeatWP\ReSmush\Helpers\SmushImage;
use OffbeatWP\Contracts\SiteSettings;

class Service extends AbstractService
{

    protected $settings;

    public function register(SiteSettings $settings)
    {
        // --------------------- Add settings page ---------------------

        $settings->addPage(AddSettings::class);

        // --------------------- WP Filters ---------------------

        //        add_filter('wp_handle_upload', [$this, 'handleUpload'], 10, 2);

        if (setting('re_smush_enabled_thumbnails') == true) {
            add_filter('wp_generate_attachment_metadata', [$this, 'handleThumbnails'], 10, 2);
        }

        // --------------------- Set default quality ---------------------

        if (setting('re_smush_image_quality') != null && setting('re_smush_image_quality') != '') {
            $this->defaultQuality = setting('re_smush_image_quality');
        } else {
            $this->defaultQuality = 90;
        }
    }

    public function handleUpload($image)
    {
        $apiCall = new SmushImage($image['type'], $image['file']);
        $apiCall->setQuality($this->defaultQuality);
        $apiCall->execute();

        return $image;
    }

    public function handleThumbnails($image, $key)
    {
        $this->smushDemention($image, $key, 'thumbnail');
        $this->smushDemention($image, $key, 'medium_large');
        $this->smushDemention($image, $key, 'medium');
        $this->smushDemention($image, $key, 'hero');

        return $image;
    }

    protected function smushDemention($image, $key, $size)
    {
        $apiCall = new SmushImage(get_post_mime_type($key), $this->getFile($image, $size));
        $apiCall->setQuality($this->defaultQuality);
        $apiCall->execute();
    }

    protected function getBasePath($image)
    {
        return substr($image["file"], 0, strrpos($image["file"], '/'));
    }

    protected function getFile($image, $size = 'thumbnail')
    {
        return wp_upload_dir()['basedir'] . '/' . $this->getBasePath($image) . '/' . $image["sizes"][$size]["file"];
    }

}