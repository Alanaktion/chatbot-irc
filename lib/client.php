<?php
// Minimal IRC Client

class IrcClient
{
    protected $socket;
    protected $config;

    /**
     * Create client instance and connect to server
     * @param string $server
     * @param int    $port
     */
    public function __construct(string $server, int $port)
    {
        $this->socket = fsockopen($server, $port);
        if (!$this->socket) {
            throw new Exception("Failed to open socket connection");
        }
    }

    /**
     * Initialize connection by logging in, setting nickname, etc.
     *
     * @param array $config
     * @return void
     */
    public function init(array $config)
    {
        $this->config = $config;
        $nick = $config['nick'];
        $this->write("USER $nick 0 * :$nick");
        $this->nick($nick);
        if (!empty($config['channel'])) {
            $this->join($config['channel']);
        }
    }

    /**
     * Set nickname
     * @param string $nickname
     * @return void
     */
    public function nick(string $nickname): void
    {
        $this->write("NICK $nickname");
    }

    /**
     * Join a channel
     * @param string $channel
     * @return void
     */
    public function join(string $channel): void
    {
        $this->write("JOIN $channel");
    }

    /**
     * Listen for incoming commands
     * @return void
     */
    public function run(): void
    {
        while (true) {
            while ($data = fgets($this->socket)) {
                echo $data;

                $ex = explode(' ', $data);
                if ($ex[0] == 'PING') {
                    $this->write("PONG {$ex[1]}");
                    continue;
                }

                // $nicka = explode('@', $ex[0]);
                // $nickb = explode('!', $nicka[0]);
                // $nickc = explode(':', $nickb[0]);

                // $host = $nicka[1];
                // $nick = $nickc[1];

                $rawcmd = explode(':', $ex[3]);
                if (isset($rawcmd[1]) && substr($rawcmd[1], 0, 1) == '!') {
                    $command = $rawcmd[1];
                    $args = null;
                    for ($i = 4; $i < count($ex); $i++) {
                        $args .= $ex[$i] . ' ';
                    }
                    $channel = $ex[2];
                    $this->command(ltrim($command, '!'), $args, $channel);
                }
            }
        }
    }

    /**
     * Write to the socket
     * @param  string $data
     * @return bool
     */
    protected function write(string $data): bool
    {
        echo "> $data\n";
        return fwrite($this->socket, "$data\n") !== false;
    }

    /**
     * Send a message to a channel
     *
     * @param string $message
     * @param string $channel
     * @return bool
     */
    public function message(string $message, string $channel): bool
    {
        return $this->write("PRIVMSG $channel :$message\n");
    }

    /**
     * Run a command by name
     *
     * If the command is not found, the call is silently ignored
     *
     * @param string $command
     * @param string $args
     * @param string $channel
     * @return bool Whether the command ran successfully
     */
    public function command(
        string $command,
        string $args,
        string $channel
    ): bool {
        if (!$command || strpos($command, ".") !== false) {
            return false;
        }

        $file = $this->findCommand($command);
        if ($file) {
            $fn = require($file);
            try {
                $result = $fn($this, $args, $channel);
                if ($result !== null) {
                    $this->message($result, $channel);
                }
                return true;
            } catch (Exception $e) {
                $this->message("ERR: " . $e->getMessage(), $channel);
            }
        } else {
            echo "Command not found: $command\n";
        }
        return false;
    }

    /**
     * Find a command PHP file by name
     *
     * @param string $command
     * @return string
     */
    protected function findCommand(string $command): string
    {
        $root = dirname(__DIR__) . '/commands/';
        if (isset($this->config['aliases'][$command])) {
            $command = $this->config['aliases'][$command];
        }
        if (is_file($root.$command.'.php')) {
            return $root.$command.'.php';
        }
        foreach (scandir($root) as $dir) {
            if ($dir{0} != '.' && is_dir($root.$dir)) {
                if (is_file($root.$dir.'/'.$command.'.php')) {
                    return $root.$dir.'/'.$command.'.php';
                }
            }
        }

        return null;
    }
}
