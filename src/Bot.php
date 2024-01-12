<?php

namespace App;

use App\Input\Reader;
use App\Input\Referee;

class Bot
{
    public const DEBUG = false;
    public const ENABLE_MESSAGE = false;
    public const DUMP_REFEREE = false;

    public const PLAYER = 1;
    public const OPPONENT = 2;

    public function run()
    {
        $game = Reader::initialize();

        // game loop
        while (true) {
            $game = Reader::readRound($game);
            if (self::DUMP_REFEREE) {
                Referee::dump();
            }

            $actions = $game->play();

            foreach ($actions as $action) {
                echo $action;
            }
            if (feof(STDIN)) {
                break;
            }
            Referee::reset();
        }
    }
}
