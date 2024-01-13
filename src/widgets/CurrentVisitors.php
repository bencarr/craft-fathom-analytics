<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use Craft;

class CurrentVisitors extends BaseWidget
{
    public static function displayName(): string
    {
        return Craft::t('fathom', 'Current Visitors');
    }

    public function getBodyHtml(): ?string
    {
        $response = FathomPlugin::getInstance()->api->getCurrentVisitors();

        return $this->renderWidget('current-visitors', [
            'response' => $response,
        ]);
    }
}
