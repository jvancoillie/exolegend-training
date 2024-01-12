<?php

namespace App\Input;

use App\Game\Game;

class Reader
{
    public static function initialize(): Game
    {
        $game = new Game();
        Referee::addEntry('>>>--------- initialize -----------');

        return $game;
    }

    public static function readRound(Game $previousGame): Game
    {
        $game = new Game($previousGame);

        return $game;
    }
}
