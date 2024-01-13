<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use Craft;

class TopPages extends BaseWidget
{
    public string $range = 'last_30_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Top Pages');
    }

    public function getBodyHtml(): ?string
    {
        $range = $this->getRanges()[$this->range];
        $response = FathomPlugin::getInstance()->api->getTopPages($range, $this->range);

        return $this->renderWidget('top-pages', [
            'response' => $response,
        ]);
    }
}
