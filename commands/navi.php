<?php
return function (IrcClient $client, string $args, string $channel) {
    $responses = array("Hey!", "Hey!", "Listen!");
    return $responses[array_rand($responses)];
};
