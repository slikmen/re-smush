<?php

namespace OffbeatWP\ReSmush\Data;

class General
{

    public static function imageQualities()
    {
        $qualities = [];

        foreach (range(0, 100) as $number) {
            $qualities[$number] = $number . '%';
        }

        return $qualities;
    }

}