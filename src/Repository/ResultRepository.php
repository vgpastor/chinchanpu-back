<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Result;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class ResultRepository
{

    private $values;

    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->values = $session->get('data_results');
        if(is_null($this->values)){
            $this->values = [];
        }
    }

    public function save(Result $result){
        $key = array_search((string)$result->getUid(), array_column($this->values, 'uid'));
        if($key){
            $this->values[$key]=$result;
        }else{
            $this->values[] = $result;
        }
        $this->session->set('data_results',$this->values);
    }

    public function getLast($number=10){
        return $this->values;
    }

    protected function serialize(Result $result){
        return json_encode(            [
                $this->uid->toString(),
                $this->dateOfGame->getTimestamp(),
                $this->enemy,
                $this->player,
                $this->winner
            ]
        )
    }

    protected function unserialize(array $data){
        return 
    }

}