<?php
/**
 * Created by PhpStorm.
 * User: vadik
 * Date: 04.01.17
 * Time: 11:08
 */

namespace Vadiktok\ChatBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Ratchet\Server\IoServer;

class SocketServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vadiktok:server:run')
            ->setDescription('Start the socket server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $chat = $this->getContainer()->get('vadiktok_chat.chat');
        $server = IoServer::factory($chat, $chat->getPort());
        $output->writeln("Socket server is running on port " . $chat->getPort());
        $server->run();
    }
}