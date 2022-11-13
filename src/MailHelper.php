<?php

namespace Flamarkt\NativeEmailOverrides;

use Flarum\Locale\Translator;

class MailHelper
{
    public static function trans(Translator $translator, string $key, array $data): string
    {
        $text = $translator->trans($key, collect($data)->mapWithKeys(function ($value, $key) {
            // Add missing braces so we don't have to do it in the template
            return ['{' . $key . '}' => $value];
        })->all());

        // Convert to HTML while preserving newlines and escaping everything else
        return implode('<br><br>', array_map(function ($plainText) {
            return e($plainText);
        }, explode("\n\n", $text)));
    }
}
