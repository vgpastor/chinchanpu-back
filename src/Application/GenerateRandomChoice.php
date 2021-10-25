<?php
declare(strict_types=1);

namespace App\Application;

use App\Entity\Result;
use function PHPUnit\Framework\throwException;

final class GenerateRandomChoice
{

    private int $retryStellarAPI = 5;

    /**
     * Generate a random result to Rock, Paper and Scissors
     */
    public function generate(): string
    {
        $hash = $this->getStellarLastHash();
        return $this->extractOptiontoHash($hash);
//        $options = Result::OPTIONS;
//        shuffle($options);
//        return array_pop($options);
    }


    private function getStellarLastHash()
    {
        if ($this->retryStellarAPI <= 0) {
            return hash('sha256', random_bytes(64));
        }
        $ch = curl_init('https://horizon.stellar.org/transactions?limit=1&order=desc');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);
        if (curl_errno($ch) !== 0) {
            $info = curl_getinfo($ch);
            throw new \Exception("Error in stellar API " . curl_errno($ch) . " -> " . json_encode($info));
        }
        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:  # OK
                    break;
                default:
                    throw new \Exception("Unexpected HTTP code " . curl_errno($ch));
            }
        }

        curl_close($ch);
        try {
            return json_decode($response)->_embedded->records[0]->hash;
        } catch (\Exception $e) {
            $this->retryStellarAPI--;
            return $this->getStellarLastHash();
        }

    }

    private function extractOptiontoHash($hash)
    {
        $char = $hash[rand(0, strlen($hash) - 1)];
        switch ($char) {
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
                return Result::OPTIONS[0];
                break;
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                return Result::OPTIONS[1];
                break;
            case 'A':
            case 'B':
            case 'C':
            case 'D':
            case 'E':
                return Result::OPTIONS[2];
                break;
            default:
                return $this->extractOptiontoHash($hash);
                break;
        }
    }
}
