<?php

class OpenDota {
    public function getPlayerInfo(int $player_id, string $types = "") {
        return json_decode(file_get_contents("https://api.opendota.com/api/players/{$player_id}/{$types}"), true);
    }
}