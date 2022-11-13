<?php

namespace Flamarkt\NativeEmailOverrides;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Mail\Job\SendRawEmailJob;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Mail\Mailer;

class MailProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->bindMethod(SendRawEmailJob::class . '@handle', function (SendRawEmailJob $job, Container $container) {
            $job->handle(new MailerProxy($container->make(Mailer::class)));
        });
    }
}
