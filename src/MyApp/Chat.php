<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $users;
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        
        $msg = explode(" ", $msg);
        switch($msg[0]){
            case "init":{
                $this->clients->attach($from, $msg[1]);
                $this->users[$msg[1]] = $from;
                $sender_id = $this->clients[$from];
                echo sprintf("User:%d init a conn!!", $sender_id);
            }break;
            case "msg":{
                $receiver_id = $msg[1];
                if (isset($this->users[$receiver_id]) && !is_null($this->users[$receiver_id])) {
                    // The sender is not the receiver, send to each client connected
                    $this->users[$receiver_id]->send($msg[2]);
                    echo sprintf("User:%d send User:%d a msg:%s", $sender_id, $receiver_id, $msg[2]);
                } else {
                    echo sprintf("User:%d is offline or not exists!!!", $receiver_id);
                }
            }break;
            default:break;
        }
        
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $sender_id = $this->clients[$conn];
        unset($this->users[$index]);
        $this->clients->detach($conn);

        echo sprintf("User:%d abandon a conn!!", $sender_id);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
?>