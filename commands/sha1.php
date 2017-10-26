<?php
return function (IrcClient $client, string $args, string $channel) {
    if (empty($params)) {
        return "Usage: !sha1 <string>";
    } else {
        return sha1(implode(" ", $params));
    }
};
