<?php
declare(strict_types=1);


namespace App\Tests\Acceptance;

use App\Application\GenerateRandomChoice;
use App\Controller\MainController;
use App\Entity\Result;
use App\Repository\ResultRepository;
use http\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class MainControllerTest extends WebTestCase
{

    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        parent::setUp();
        if(!is_null($this->client)){
            return;
        }

        $this->client = static::createClient();
    }

    public function testGeneration(){

        $this->client->request('POST',"/generate",['player'=>'rock'],[],['HTTP_CONTENT_TYPE' => 'application/json']);
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($response['player'],'rock');
        if($response['enemy']=="rock"){
            $this->assertEquals($response['winner'],0);
        }
        if($response['enemy']=="paper"){
            $this->assertEquals($response['winner'],-1);
        }
        if($response['enemy']=="scissors"){
            $this->assertEquals($response['winner'],1);
        }
    }

    public function testGenerationError(){

        $this->client->request('POST',"/generate",['player'=>'iron'],[],['HTTP_CONTENT_TYPE' => 'application/json']);
        $this->assertResponseStatusCodeSame(400);
    }


    /**
     * @depends testGeneration
     */
    public function testHistory()
    {
        $session = $this->client->getContainer()->get('session');
        $resultDemo = new Result('rock','paper');
        $session->set(ResultRepository::NAME_STORAGE,[$resultDemo] );

        $this->client->request('GET',"/history",[],[],['HTTP_CONTENT_TYPE' => 'application/json']);
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertGreaterThan(0,count($response));
    }

    public function testHistoryClean()
    {
        $this->client->request('DELETE',"/history",[],[],['HTTP_CONTENT_TYPE' => 'application/json']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(202);
        $this->client->request('GET',"/history",[],[],['HTTP_CONTENT_TYPE' => 'application/json']);
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(0, $response);
    }


}
