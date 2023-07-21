<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Symfony\Component\Mime\Email;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage())
            ->withSymfonyMessage(static function (Email $message): void {
                $message->getHeaders()->addTextHeader('X-Template', static::class);
            });
    }
}
