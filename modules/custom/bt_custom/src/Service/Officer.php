<?php

namespace Drupal\bt_custom\Service;


/**
 * Class Officer
 * @var
 * @package Drupal\bt_custom\Service
 */
class Officer {
    private $services;

    public function __construct(iterable $services) {
        $this->services = $services;
        \Drupal::logger('ttt')->notice('<pre>'.print_r($this->services, TRUE).'</pre>');
    }

    public function officerSays($string) {

    }
}