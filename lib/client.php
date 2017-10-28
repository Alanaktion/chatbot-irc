<?php
// Minimal IRC Client

class IrcClient
{
    protected $socket;
    protected $config;
    protected $lastCommand;

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
            if (is_array($config['channel'])) {
                foreach ($config['channel'] as $channel) {
                    $this->join($channel);
                }
            } else {
                $this->join($config['channel']);
            }
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

                $ex = explode(' ', rtrim($data, "\n"));
                if ($ex[0] == 'PING') {
                    $this->write("PONG {$ex[1]}");
                    continue;
                }

                // $nicka = explode('@', $ex[0]);
                // $nickb = explode('!', $nicka[0]);
                // $nickc = explode(':', $nickb[0]);

                // $host = $nicka[1];
                // $nick = $nickc[1];

                if ($ex[1] == 'PRIVMSG' && @$ex[3]{1} == '!') {
                    $channel = $ex[2];
                    if (ltrim($ex[3], ':') == '!!') {
                        echo "!!\n";
                        if (isset($this->lastCommand[$channel])) {
                            $last = $lastCommand[$channel];
                            echo "Running last command: {$last[0]}\n";
                            $this->command($last[0], $last[1], $channel);
                        } else {
                            echo "No previous command\n";
                            $this->message('No previous command.', $channel);
                        }
                    } else {
                        $command = ltrim($ex[3], ':!');
                        $args = '';
                        for ($i = 4; $i < count($ex); $i++) {
                            $args .= $ex[$i] . ' ';
                        }
                        $this->command(trim($command), trim($args), $channel);
                    }
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

        $this->lastCommand[$channel] = [$command, $args];

        $file = $this->findCommand($command);
        if ($file) {
            $fn = require($file);
            try {
                $result = $fn($this, $args, $channel);
                if ($result !== null) {
                    $lines = explode("\n", trim($result, "\n"));
                    foreach ($lines as $line) {
                        $this->message($line, $channel);
                    }
                }
                return true;
            } catch (Exception $e) {
                $this->message("ERR: " . $e->getMessage(), $channel);
            }
        } else {
            print_r([$command,$args,$channel]);
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
    protected function findCommand(string $command): ?string
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
