<?php
declare(strict_types=1);


namespace App\Tests\Application;

use App\Application\GenerateRandomChoice;
use App\Entity\Result;
use PHPUnit\Framework\TestCase;

class GenerateRandomChoiceTest extends TestCase
{
    private GenerateRandomChoice $generateRandomChoice;
    public function setUp(): void
    {
        parent::setUp();
        $this->generateRandomChoice = new GenerateRandomChoice();
    }


    public function testGenerate()
    {
        $choice = $this->generateRandomChoice->generate();
        $this->assertContains($choice,Result::OPTIONS);
    }

    public function testAleatory()
    {
        $results = [];
        for($i=0;$i<100;$i++){
            $choice = $this->generateRandomChoice->generate();
            @$results[$choice]++;
        }
        foreach ($results as $key=>$value){
            $this->assertGreaterThan(25,$value,$key." lower than minimum");
        }
    }

}
