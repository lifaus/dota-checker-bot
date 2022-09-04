<?php

class Server {
    public Telegram $api;
    private ?array $config = null;
    private int $update_id = 0;

    public function __construct() {
        $this->config = json_decode(file_get_contents("./config.json"), true);
        $this->api = new Telegram($this->config['telegram_api_bot_key']);
    }

    public function start() {
        while (true) {
            foreach ($this->api->request('getUpdates', [
                'offset' => $this->update_id
            ]) as $updates) {
                if (!isset($updates[0])) continue;
                $this->update_id = $updates[0]['update_id'] + 1;
                
                //Check messages
                $message = new MessagesChecker($updates[0], $this->api);
                $message->check();
            }
        }
    }
}