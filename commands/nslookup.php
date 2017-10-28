<?php
return function (IrcClient $client, string $args, string $channel) {
    if ($args) {
        $result = gethostbyname(explode(' ', $args)[0]);
        if (!empty($result)) {
            return $result;
        } else {
            return 'No matching hosts found.';
        }
    } else {
        return 'Usage: !nslookup <host>';
    }
};
