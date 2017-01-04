<?php
/**
 * Created by PhpStorm.
 * User: vadik
 * Date: 04.01.17
 * Time: 13:42
 */

namespace Vadiktok\ChatBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class MessageEvent extends Event
{
    protected $message;

    public function __construct($message) {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    public static function getName() {
        return 'vadiktok_chat.message';
    }
}
