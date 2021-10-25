<?php
declare(strict_types=1);

namespace App\Application;

use App\Entity\Result;

final class GenerateRandomChoice
{

    /**
     * Generate a random result to Rock, Paper and Scissors
     */
    public function generate(): string
    {
        $options = Result::OPTIONS;
        array_shift($options);
        return array_pop($options);
    }
}