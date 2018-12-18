<?php
$rabinkarp = new Rabinkarp_model();
echo $rabinkarp->rabinkarp("lorem ipsum dolor sit amet","shit amet lorem dolor is",4);

class Rabinkarp_model{

    //ALGORITMA RABIN KARP
    function rabinkarp($teks1, $teks2, $gram) {
        $data['teks1White'] 	= $this->whiteInsensitive($teks1);
        $data['teks2White'] 	= $this->whiteInsensitive($teks2);
        $data['teks1Gram'] 		= $this->kGram($data['teks1White'], $gram);
        $data['teks2Gram'] 		= $this->kGram($data['teks2White'], $gram);
        $data['teks1Hash'] 		= $this->hash($data['teks1Gram']);
        $data['teks2Hash'] 		= $this->hash($data['teks2Gram']);
        $data['fingerprint'] 	= $this->fingerprint($data['teks1Hash'], $data['teks2Hash']);
        $data['similiarity'] 	= $this->SimilarityCoefficient($data['fingerprint'], $data['teks1Hash'], $data['teks2Hash']);
        return $data['similiarity'];
    }

    function whiteInsensitive($teks) {
        
        $a = $teks;
        $b = preg_replace("/[^a-z0-9_\-\.]/i", "|", $a);
        $c = explode("|", $b); //misah string berdasarkan |
        $e = '';
        $f = '';
        for ($d = 0; $d < count($c); $d++) {
            if (trim($c[$d]) != "")
                $e .= $c[$d] . " ";
        }
        $e = strtolower(substr($e, 0, strlen($e) - 1));
        $f = str_replace(array(".", "_"), "", $e);
        return $f;
    }

    
    function kGram($teks, $gram=3) {
        $i = 0;
        $length = strlen($teks);
        $teksSplit;
        if (strlen($teks) < $gram) {
            $teksSplit[] = $teks;
        } else {
            for ($i; $i <= $length - $gram; $i++) {
                $teksSplit[] = substr($teks, $i, $gram);
            }
        }

        return $teksSplit;
    }

    
    function hash($gram) {
        $hashGram = null;
        foreach ($gram as $a => $teks) {
            if ($this->cekHash($teks, $hashGram) != true) {
                $hashGram[] = $this->rollingHash($teks);
            }
        }

        return $hashGram;
    }

    
    function rollingHash($string) {
        $basis = 11;
        $pjgKarakter = strlen($string);
        $hash = 0;
        for ($i = 0; $i < $pjgKarakter; $i++) {
            $ascii = ord($string[$i]);
            $hash += $ascii * pow($basis, $pjgKarakter - ($i + 1));
        }
        return $hash;
    }

    
    function fingerprint($hash1, $hash2) {
        $fingerprint = null;
        $hashUdahDicek = null;
        $sama = false;
        $countHash1 = count($hash2);
        for ($i = 0; $i < count($hash1); $i++) {
            for ($j = 0; $j < $countHash1; $j++) {
                if ($hash1[$i] == $hash2[$j]) {
                    if ($this->cekHash($hash1[$i], $hashUdahDicek) == false) {
                        $fingerprint[] = $hash1[$i];
                    }
                    $sama = true;
                } else {
                    $sama = false;
                }
            }
            if ($sama == true) {
                $hashUdahDicek[] = $hash1[$i];
            }
        }
        return $fingerprint;
    }

   
    function cekHash($hash, $hashUdahDicek) {
        $value = false;
        $countHashUdahDicek = count($hashUdahDicek);
        if ($countHashUdahDicek > 0) {
            for ($k = 0; $k < $countHashUdahDicek; $k++) {
                if ($hashUdahDicek[$k] == $hash) {
                    $value = true;
                    break;
                }
            }
        }
        return $value;
    }

   
    function similarityCoefficient($fingerprint, $hash1, $hash2) {
        return number_format(((2 * count($fingerprint) / (count($hash1) + count($hash2))) * 100), 2, '.', '');
    }

}
