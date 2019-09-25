<?php

namespace OffbeatWP\ReSmush;

class AddSettings
{

    const ID = 're-smush';

    const PRIORITY = 1;

    public function title()
    {
        return __('Re smush', 'raow');
    }

    public function form()
    {
        $form = new \OffbeatWP\Form\Form();
        $form->addTab('localseo_company_general-information', 'OffbeatWP Image optimiser');
        $form->addField(\OffbeatWP\Form\Fields\Text::make('re_smush_image_quality', 'Image Quality'));

        return $form;
    }

}