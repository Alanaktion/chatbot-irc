<?php
return function (IrcClient $client, string $args, string $channel) {
    return date('r');
};
