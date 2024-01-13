<?php

namespace bencarr\fathom\models;

use craft\base\Model;

class Settings extends Model
{
    public string $apiKey = '';
    public string $siteId = '';

    public function defineRules(): array
    {
        return [
            [['apiKey', 'siteId'], 'required'],
        ];
    }
}
