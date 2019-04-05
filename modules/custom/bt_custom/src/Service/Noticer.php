<?php

namespace Drupal\bt_custom\Service;
use Drupal\Core\Messenger\Messenger;

/**
 * Class Noticer
 * @var
 * @package Drupal\bt_custom\Service
 */
class Noticer {
    private $myMessenger;

    public function __construct(Messenger $messenger) {
        $this->myMessenger = $messenger;
    }

    public function setNotice($string) {
        $this->myMessenger->addMessage($string);
    }
}