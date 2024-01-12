<?php

namespace App\Game;

class Action
{
    public const MOVE = 'MOVE';
    public const WAIT = 'WAIT';
    private $instruction;
    private $message;

    public function getInstruction()
    {
        return $this->instruction;
    }

    public function setInstruction($instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): self
    {
        $this->message = $message;

        return $this;
    }

    public function __toString(): string
    {
        return trim(sprintf('%s %s', $this->instruction, $this->message))."\n";
    }
}
