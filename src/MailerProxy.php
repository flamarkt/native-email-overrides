<?php

namespace Flamarkt\NativeEmailOverrides;

use Flarum\Http\UrlGenerator;
use Flarum\Locale\Translator;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Arr;

class MailerProxy implements Mailer
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function to($users)
    {
        return $this->mailer->to($users);
    }

    public function bcc($users)
    {
        return $this->mailer->bcc($users);
    }

    public function raw($text, $callback)
    {
        /*$generator = resolve(UrlGenerator::class);

        $confirmEmailUrl = preg_quote($generator->to('forum')->route('confirmEmail', ['token' => 'TOKEN']));

        if (preg_match('~' . str_replace('TOKEN', '[a-z0-9]+', $confirmEmailUrl) . '~', $text, $matches) === 1) {
            $this->send([
                'html' => 'flamarkt-native-email-overrides::email.confirmEmail',
            ], [
                'originalText' => $text,
                'confirmLink' => $matches[0],
            ], $callback);
        }*/

        // A mapping of the original translation key which we will look for, with the list of translation attributes and the replacement HTML view
        $flarumEmails = [
            // Flarum\User\AccountActivationMailerTrait
            'core.email.activate_account.body' => [
                'data' => ['username', 'url', 'forum'],
                'view' => 'flamarkt-native-email-overrides::email.activateAccount',
            ],
            // Flarum\User\Command\RequestPasswordResetHandler
            'core.email.reset_password.body' => [
                'data' => ['username', 'url', 'forum'],
                'view' => 'flamarkt-native-email-overrides::email.resetPassword',
            ],
            // Flarum\User\EmailConfirmationMailer
            'core.email.confirm_email.body' => [
                'data' => ['username', 'url', 'forum'],
                'view' => 'flamarkt-native-email-overrides::email.confirmEmail',
            ],
        ];

        $translator = resolve(Translator::class);

        foreach ($flarumEmails as $translationKey => $flarumEmail) {
            $translatorData = [];

            foreach ($flarumEmail['data'] as $key) {
                $translatorData['{' . $key . '}'] = '__' . $key . '__';
            }

            $body = preg_quote($translator->get($translationKey, $translatorData));

            foreach ($flarumEmail['data'] as $key) {
                $body = str_replace('__' . $key . '__', '(?<' . $key . '>.*)', $body);
            }

            if (preg_match('~^' . $body . '$~', $text, $matches) === 1) {
                $this->send([
                    'html' => $flarumEmail['view'],
                ], [
                    'originalTranslatorData' => Arr::only($matches, $flarumEmail['data']),
                    'confirmLink' => $matches[0],
                ], $callback);

                return;
            }
        }

        $this->mailer->raw($text, $callback);
    }

    public function send($view, array $data = [], $callback = null)
    {
        $this->mailer->send($view, $data, $callback);
    }

    public function failures()
    {
        return $this->mailer->failures();
    }
}
