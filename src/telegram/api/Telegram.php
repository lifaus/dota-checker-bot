<?php

class Telegram {
    private ?string $telegram_api_key = null;
    const API_URL = "https://api.telegram.org/bot";

    public function __construct(string $telegram_api_key) {
        $this->telegram_api_key = $telegram_api_key;
    }

    public function request(string $method = "", array $params = []) {
        $url = Telegram::API_URL . $this->telegram_api_key . "/" . $method . "?";
        return json_decode(file_get_contents($url.http_build_query($params)), true);
    }

    public function sendMessage(string $message, int $chat_id) {
        return $this->request('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message
        ]);
    }

}

?>