<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use Craft;

class TopReferrers extends BaseWidget
{
    public string $range = 'last_30_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Top Referrers');
    }

    public function getBodyHtml(): ?string
    {
        $range = $this->getRanges()[$this->range];
        $response = FathomPlugin::getInstance()->api->getTopReferrers($range, $this->range);

        return $this->renderWidget('top-referrers', [
            'response' => $response,
        ]);
    }
}
