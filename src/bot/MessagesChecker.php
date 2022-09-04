<?php

class MessagesChecker {
    public Telegram $api;

    public ?int $user_id = null;
    public ?int $chat_id = null;
    public ?array $message_object = null;

    public function __construct(array $message_object, Telegram $api) {
        $this->api = $api;
        $this->message_object = $message_object;
    }

    public function check() {
        $this->user_id = $this->message_object['message']['from']['id'];
        $this->chat_id = $this->message_object['message']['chat']['id'];
        $text_message = $this->message_object['message']['text'];

        if ($text_message == "/start") {
            $this->api->sendMessage("test", $this->chat_id);
        }

    }
}