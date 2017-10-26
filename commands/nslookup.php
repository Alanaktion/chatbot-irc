<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!empty($params[0])) {
        $result = gethostbyname($params[0]);
        if (!empty($result)) {
            return $result;
        } else {
            return "No matching hosts found.";
        }
    } else {
        return "Usage: !nslookup <host>";
    }
};
