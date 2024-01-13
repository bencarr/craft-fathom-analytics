<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use Craft;

class Overview extends BaseWidget
{
    public string $range = 'last_30_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Overview');
    }

    public function getBodyHtml(): ?string
    {
        $range = $this->getRanges()[$this->range];
        $response = FathomPlugin::getInstance()->api->getOverview($range, $this->range);

        return $this->renderWidget('overview', [
            'response' => $response,
        ]);
    }
}
