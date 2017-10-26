<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!empty($params[0])) {
        return strrev(implode(" ", $params));
    } else {
        return "Usage: !backwards <words, yo>";
    }
};
