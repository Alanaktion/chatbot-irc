<?php
return function (IrcClient $client, string $args, string $channel) {
    $params = explode(' ', $args);
    if (!empty($params[0]) && intval($params[0]) > 0) {
        $n = intval($params[0]);

        if ($n > 10000000) {
            return "Max allowed: 10,000,000";
        }

        $max = "No prime number found";

        switch ($n) {
            case 1:
                break;
            case 2:
            case 3:
                $max = $n;
                break;
            default:
                // Give more memory if generating massive primes
                if ($n > 3000000) {
                    ini_set("memory_limit", "512M");
                }

                // Adapted from http://damianoferrari.com/php-prime-number-generator/
                if ($n < 5) {
                    return (($n < 2) ? false : (($n < 3) ? array(2) : array(2, 3)));
                }

                $primes = range(3, $n, 2);
                for ($i = 0, $sqrtN = sqrt($n), $pTot = count($primes); $j = 0, $k = $primes[$i], $k <= $sqrtN; $i++) {
                    if ($k == null) {
                        continue;
                    }
                    while (++$j * $k < $pTot) {
                        $primes[$j * $k + $i] = null;
                    }
                }

                array_unshift($primes, 2);
                $result = array_values(array_filter($primes));

                $max = $result[count($result) - 1];
        }

        if (!empty($params[1]) && intval($params[1]) && isset($result)) {
            $display_result = array_slice($result, -$params[1]);
            return implode(", ", $display_result);
        } else {
            return $max;
        }
    } else {
        return "Usage: !prime <max> [count]";
    }
};
