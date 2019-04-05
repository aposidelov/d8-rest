<?php

namespace Drupal\bt_custom\Service;
use Drupal\Core\Session\AccountProxy;
use Drupal\bt_custom\Service\Noticer;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

class Shower {
    use StringTranslationTrait;
    private $myNoticer;
    private $curAccount;
    private $translation;
    private $params;
    public function __construct(Noticer $noticer, AccountProxy $account, TranslationInterface $string_translation, array $params) {
        $this->myNoticer = $noticer;
        $this->curAccount = $account;
        $this->translation = $string_translation;
        $this->params = $params;
    }

    public function show() {
        $this->myNoticer->setNotice('<pre>'.print_r($this->params, TRUE).'</pre>');
        $this->myNoticer->setNotice($this->t('shower shows, uid:') . $this->curAccount->id());
    }
}