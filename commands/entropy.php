<?php
return function (IrcClient $client, string $args, string $channel) {
    if ($args) {
        $len = strlen($args);
        $entropy =  4 * min($len, 1) + ($len > 1 ? (2 * (min($len, 8) - 1)) : 0) + ($len > 8 ? (1.5 * (min($len, 20) - 8)) : 0) + ($len > 20 ? ($len - 20) : 0) + 6 * (bool)(preg_match('/[A-Z].*?[0-9[:punct:]]|[0-9[:punct:]].*?[A-Z]/', $args));

        if ($entropy < 20) {
            $result = "Weak";
        } elseif ($entropy < 25) {
            $result = "Moderate";
        } else {
            $result = "Strong";
        }

        return $entropy . " ($result)";
    } else {
        return "Usage: !entropy <password>";
    }
};
