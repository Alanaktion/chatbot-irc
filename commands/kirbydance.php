<?php
return function (IrcClient $client, string $args, string $channel) {
    $client->message("<(^.^<)", $channel);
    sleep(1);
    $client->message("(>^.^)>", $channel);
    sleep(1);
    $client->message("<(^.^<)", $channel);
    sleep(1);
    $client->message("(>^.^)>", $channel);
    sleep(1);
    $client->message("^( ^.^ )^", $channel);
};
