<?php

namespace Flamarkt\NativeEmailOverrides;

use Flarum\Extend;

return [
    (new Extend\Locales(__DIR__ . '/locale')),

    (new Extend\View())
        ->namespace('flamarkt-native-email-overrides', __DIR__ . '/views'),

    (new Extend\ServiceProvider())
        ->register(MailProvider::class),
];
