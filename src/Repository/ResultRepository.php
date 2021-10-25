<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Result;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class ResultRepository
{

    private $values;

    private SessionInterface $session;

    private const NAME_STORAGE = "data_results2";

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->values = $session->get(self::NAME_STORAGE);
        if(is_null($this->values)){
            $this->values = [];
        }
    }

    public function save(Result $result){
        $key = array_search((string)$result->getUid(), array_column($this->values, 'uid'), true);
        if($key){
            $this->values[$key]=$result;
        }else{
            $this->values[] = $result;
        }
        $this->session->set(self::NAME_STORAGE,$this->values);
    }

    /**
     * @todo limit number of results
     * @param int $number
     * @return array|mixed
     */
    public function getLast(int $number=10){
        return array_slice($this->session->get(self::NAME_STORAGE), (int)("-".$number));
    }

    public function clean():bool{
        $this->session->set(self::NAME_STORAGE,[]);
        $this->values = [];
        return true;
    }

}
