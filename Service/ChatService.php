<?php
/**
 * Created by PhpStorm.
 * User: vadik
 * Date: 04.01.17
 * Time: 11:07
 */

namespace Vadiktok\ChatBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vadiktok\ChatBundle\Event\MessageEvent;

class ChatService implements MessageComponentInterface {

    /**
     * @var EntityManager
     */
    protected $em;

    protected $dispatcher;

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
    public function __construct(EntityManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;

        $this->dispatcher = $dispatcher;

        $this->clients = new ArrayCollection();
    }

    function onOpen(ConnectionInterface $conn) {
        $this->clients->add($conn);
    }

    function onClose(ConnectionInterface $conn) {
        $this->clients->removeElement($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg) {

        $event = new MessageEvent($msg);

        $msg = $this->dispatcher->dispatch(MessageEvent::getName(), $event)->getMessage();

        foreach ($this->clients as $client) {
            $client->send($msg . "\n");
        }
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function getPort() {
        return $this->port;
    }
}
