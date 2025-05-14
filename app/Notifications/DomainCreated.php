<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DomainCreated extends Notification
{
    use Queueable;

    public $domain;
    public $page;

    public function __construct($domain, $page)
    {
        $this->domain = $domain;
        $this->page = $page;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Новая модальная страница создана')
            ->line('Домен: ' . $this->domain->domain)
            ->line('Страница: ' . $this->page->page)
            ->line('Заголовок: ' . $this->page->title)
            ->line('Описание: ' . $this->page->description)
            ->line('Спасибо, что используете наш сервис!');
    }
}
