<?php
declare(strict_types=1);

namespace App\Entity;

use App\Exceptions\ChoiceException;
use App\Exceptions\NotDefinedException;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Result implements \Serializable
{

    CONST OPTION_SCISSORS = 'scissors';
    CONST OPTION_PAPER = 'paper';
    CONST OPTION_ROCK = 'rock';
    CONST OPTIONS = [self::OPTION_PAPER,self::OPTION_ROCK,self::OPTION_SCISSORS];

    private UuidInterface $uid;

    private \DateTime $dateOfGame;

    private string $enemy;

    private string $player;

    private int $winner;

    /**
     * @throws ChoiceException
     * @throws NotDefinedException
     */
    public function __construct($player, $enemy)
    {
        if(!in_array($player,self::OPTIONS)){
            throw new ChoiceException($player." Player selection it's not allowed");
        }
        if(!in_array($enemy,self::OPTIONS)){
            throw new ChoiceException($enemy." Enemy selection it's not allowed");
        }
        $this->player = $player;
        $this->enemy = $enemy;
        $this->dateOfGame = new \DateTime();
        $this->uid = Uuid::uuid4();
        $this->winner = $this->calculateWinner();
    }

    /**
     * @return int 0->tie 1->Player -1->Enemy
     * @throws NotDefinedException
     */
    private function calculateWinner(): int
    {
        if(is_null($this->player) || is_null($this->enemy)){
            throw new NotDefinedException("Player and Enemy it's not defined");
        }
        if($this->player === $this->enemy ){
            return 0;
        }

        if($this->player === self::OPTION_SCISSORS && $this->enemy === self::OPTION_ROCK){
            return -1;
        }
        if($this->player === self::OPTION_SCISSORS && $this->enemy === self::OPTION_PAPER){
            return 1;
        }

        if($this->player === self::OPTION_ROCK && $this->enemy === self::OPTION_PAPER){
            return -1;
        }
        if($this->player === self::OPTION_ROCK && $this->enemy === self::OPTION_SCISSORS){
            return 1;
        }

        if($this->player === self::OPTION_PAPER && $this->enemy === self::OPTION_SCISSORS){
            return -1;
        }
        if($this->player === self::OPTION_PAPER && $this->enemy === self::OPTION_ROCK){
            return 1;
        }

    }

    public function getUid(): UuidInterface
    {
        return $this->uid;
    }

    public function toArray(){
        return[
            'uid'=>$this->uid->toString(),
            'dateOfGame'=>$this->dateOfGame->getTimestamp(),
            'enemy'=>$this->enemy,
            'player'=>$this->player,
            'winner'=>$this->winner
        ];
    }

    public function serialize(): ?string
    {
        return serialize(
            [
                $this->uid->toString(),
                $this->dateOfGame->getTimestamp(),
                $this->enemy,
                $this->player,
                $this->winner
            ]
        );
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->uid = Uuid::fromString($data[0]);
        $this->dateOfGame = new \DateTime($data[1]);
        $this->enemy = $data[2];
        $this->player = $data[3];
        $this->winner = $data[4];
    }
}