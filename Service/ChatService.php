<?php
/**
 * Created by PhpStorm.
 * User: vadik
 * Date: 04.01.17
 * Time: 11:07
 */

namespace Vadiktok\ChatBundle\Service;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class ChatService implements MessageComponentInterface {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var ConnectionInterface[]
     */
    protected $clients;

    protected $port;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct($em)
    {
        $this->em = $em;

        $this->clients = new \SplObjectStorage;
    }

    function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            $client->send($msg . "\n");
//            if ($from !== $client) {
//                // The sender is not the receiver, send to each client connected
//                $client->send($msg);
//            }
        }
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function getPort() {
        return $this->port;
    }
}