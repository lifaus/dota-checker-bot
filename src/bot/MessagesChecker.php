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
                //Get player data using OpenDota API
                $player = $this->dota_api->getPlayerInfo($text_message_split[1]);
                if (!isset($player['profile'])) {
                    return $this->api->sendPhoto("🔒 Player not found", new Imagick("./assets/backgrounds/not_found_profile.png"), $this->chat_id);
                }
                //Get Rank Tier
                $rank = $player['rank_tier'];
                $rank = is_null($rank) ? null : (string)$rank;

                //Create picture
                $im = new Imagick("./assets/backgrounds/background.png");
                if (!is_null($rank)) {
                    $x = 910; $y = 275;
                    $rank_icon = new Imagick("./assets/rank_icons/rank_icon_{$rank[0]}.png");
                    $rank_icon_star = new Imagick("./assets/rank_star_icons/rank_star_{$rank[1]}.png");
                    $im->compositeImage($rank_icon, Imagick::COMPOSITE_DEFAULT, $x, $y);
                    $im->compositeImage($rank_icon_star, Imagick::COMPOSITE_DEFAULT, $x, $y - 30);
                } else {
                    $rank_icon = new Imagick("./assets/rank_icons/rank_icon_0.png");
                    $im->compositeImage($rank_icon, Imagick::COMPOSITE_DEFAULT, $x, $y);
                }
                
                $avatar = new Imagick($player['profile']['avatarfull']);
                $avatar->roundCorners(300, 300);
                $im->compositeImage($avatar, Imagick::COMPOSITE_DEFAULT, 130, 270);

                $this->api->sendPhoto("👤 Player {$player['profile']['personaname']}\n🆔 SteamID: {$player['profile']['steamid']}", $im, $this->chat_id);

                //$wl = $this->dota_api->getPlayerInfo($text_message_split[1], "wl");
                //$text = "Avatar: {}\nApproximate rating: {$player['mmr_estimate']['estimate']} MMR\nWin/Lose: ✅ {$wl['win']} ❌{$wl['lose']}";
                //$this->api->sendMessage($text, $this->chat_id);
            break;

            case '/match':
                
            break;
                
        }

        

    }
}