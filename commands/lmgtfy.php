<?php
return function (IrcClient $client, string $args, string $channel) {
    if (empty($params)) {
        return "Usage: !lmgtfy <query>";
    } else {
        return "http://lmgtfy.com/?q=" . urlencode(implode(" ", $params));
    }
};
