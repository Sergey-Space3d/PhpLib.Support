<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

define('SELECTOR_FAQLEDGER_ID', 'sel_id_faqledger');

class SupportDb
{
    private static $name = null;
    public static function get_name(){ return self::$name; }
    public static function set_name($name){ self::$name = $name; }
}

// Setup JIT class autoload
$__dir = dirname(__FILE__);
$__classes = array(
    'FaqLedgerMan'=>$__dir.'/faq_ledger_man.php',
    'FaqLedger'=>$__dir.'/faq_ledger.php',
    'FaqMan'=>$__dir.'/faq_man.php',
    'Faq'=>$__dir.'/faq.php',
);
require_once($__dir.'/../db/extra/c_autoloader.php');
CAutoloader::register($__classes);
?>
