<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class Handler extends WebhookHandler {
    public function start() {
        $this->chat->message(__('greeting'))->send();
    }
}
