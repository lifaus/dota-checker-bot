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

    public function sendPhoto(string $message, ?Imagick $photo = null, int $chat_id = null) {
        $bot_url = Telegram::API_URL.$this->telegram_api_key."/";
        $url = $bot_url . "sendPhoto?chat_id=" . $chat_id ;

        $image_name = rand().".png";
        $photo->writeImage("./temp/".$image_name);

        $post_fields = [
            "caption" => $message,
            'chat_id' => $chat_id,
            'photo' => new CURLFile("./temp/".$image_name)
        ];

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type:multipart/form-data"
        ]);
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
        $output = curl_exec($ch);

        return @unlink("./temp/".$image_name);
    }

}

?>