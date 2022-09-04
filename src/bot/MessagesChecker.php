<?php

class MessagesChecker {
    public Telegram $api;
    public OpenDota $dota_api;

    public ?int $user_id = null;
    public ?int $chat_id = null;
    public ?array $message_object = null;

    public function __construct(array $message_object, Telegram $api) {
        $this->api = $api;
        $this->dota_api = new OpenDota;
        $this->message_object = $message_object;
    }

    public function check() {
        $this->user_id = $this->message_object['message']['from']['id'];
        $this->chat_id = $this->message_object['message']['chat']['id'];
        $text_message = $this->message_object['message']['text'];
        $text_message_split = explode(" ", $text_message);

        switch ($text_message_split[0]) {
            case '/start':
                $this->api->sendMessage("Hello, I am a bot for dota 2 account information or match details.\n\nFor a list of commands: /help", $this->chat_id);
            break;
            
            case '/help':
                $this->api->sendMessage("Commands list:\n\n/player [SteamID32] - get information about dota account\n/match [matchID] - get information about the match", $this->chat_id);
            break;

            case '/player':
                $player = $this->dota_api->getPlayerInfo($text_message_split[1]);
                $wl = $this->dota_api->getPlayerInfo($text_message_split[1], "wl");
                $text = "Avatar: {$player['profile']['avatarfull']}\nApproximate rating: {$player['mmr_estimate']['estimate']} MMR\nWin/Lose: ✅ {$wl['win']} ❌{$wl['lose']}";
                $this->api->sendMessage($text, $this->chat_id);
            break;

            case '/match':
                //TODO
            break;
                
        }

        

    }
}
