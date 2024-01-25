# Fathom Analytics

A Craft CMS plugin to surface your [Fathom Analytics](https://usefathom.com/) data in the Craft control panel.

## Features
This plugin is intended to help content authors get a nice overview of site analytics, and surface some contextual information they can use to tailor their content changes. It’s a companion, not a replacement for your Fathom Analytics dashboard. 

### Dashboard Widgets
Fantastic dashboard widgets with customizable date ranges to keep an eye on what’s most important to you:
- **Current Visitors** — How many people are on your site right now, where are they, and where were they referred from?
- **Browsers** — Breakdown of your traffic by browser.
- **Device Type** — Breakdown of your traffic by desktop, mobile, and tablet.
- **Top Pages** — List of your top 10 pages by visits and views.
- **Top Referrers** — List of your top 10 referrers by visits and views.
- **Visitors** — Chart of visitors and pageviews over time.
- **Overview** — Basic totals for number of visits, number of visitors, average time on site, and bounce rate.

### Entry Sidebar Widget
A mini widget with entry-specific analytics right in the entry edit form with tabs to cover all the basics:
- **Overview** — Visitors, views, average time on page, and bounce rate.
- **Visitors** — Chart of visitors and pageviews over time.
- **Browsers** — Breakdown of traffic by browser.
- **Devices** — Breakdown of traffic by desktop, mobile, and tablet.
- **Referrers** — List of the top 10 referrers by visits and views.

## Requirements

This plugin requires Craft CMS 4.5.0 or later, and PHP 8.0.2 or later.

## Installation

You can install this plugin from the Plugin Store or with Composer.

### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “Fathom Analytics”. Then press “Install”.

### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require bencarr/craft-fathom-analytics

# tell Craft to install the plugin
./craft plugin/install fathom
```

## Configuration

Once the plugin is installed, you’ll need to configure your API Key and Site ID either using the plugin settings page or a configuration file. You can [generate an API key](https://app.usefathom.com/api/create) from your Fathom Analytics account, and [find your site ID](https://app.usefathom.com/sites) by clicking the site from your sites list.

### From the Plugin Settings Page

On an environment with admin changes enabled, open Settings then press “Fathom Analytics” under the “Plugins” section. Your API key is sensitive data, so you’ll probably want to use an environment variable that you keep out of version control.

### From a Configuration File

Create a file called `fathom.php` in your `config/` directory.

```php
<?php

use craft\helpers\App;

return [
    '*' => [
        'apiKey' => App::env('FATHOM_API_KEY'),
        'siteId' => App::env('FATHOM_SITE_ID'),
    ],
];
```

## Extending

### Modifying Available Date Ranges

The plugin comes with a set of default ranges covering a scale of time from 1 day to 1 year. You can modify these ranges using the `EVENT_DEFINE_WIDGET_RANGES` event.

#### Default Ranges

| Range         | Key           |
|---------------|---------------|
| Last 24 Hours | `today`         |
| Last 7 Days   | `last_7_days`   |
| Last 14 Days  | `last_14_days`  |
| Last 30 Days  | `last_30_days`  |
| Last 60 Days  | `last_60_days`  |
| Last 90 Days  | `last_90_days`  |
| Last 180 Days | `last_180_days` |
| Last 365 Days | `last_365_days` |

#### Removing a Default Range

```php
use bencarr\fathom\events\RegisterWidgetRangesEvent;
use bencarr\fathom\FathomPlugin;
use craft\base\Event;

Event::on(
    FathomPlugin::class, 
    FathomPlugin::EVENT_DEFINE_WIDGET_RANGES, 
    function (RegisterWidgetRangesEvent $event) {
        unset($event->ranges['last_365_days'])
    }
);
```

#### Adding a Custom Range

The ranges event expects a keyed array of `WidgetDateRange` objects. To add a new range, add a new key to the `ranges` array on the event and construct a WidgetDateRange instance with your desired configuration:

```php
use bencarr\fathom\events\RegisterWidgetRangesEvent;
use bencarr\fathom\helpers\WidgetDateRange;
use bencarr\fathom\FathomPlugin;
use craft\base\Event;
use craft\helpers\DateTimeHelper;

Event::on(
    FathomPlugin::class, 
    FathomPlugin::EVENT_DEFINE_WIDGET_RANGES, 
    function (RegisterWidgetRangesEvent $event) {
        $event->ranges['last_2_years'] = new WidgetDateRange(
            label: 'Last 2 Years',
            start: DateTimeHelper::tomorrow()->sub(new DateInterval('P2Y')),
            end: DateTimeHelper::tomorrow(),
        );
    }
);
```
