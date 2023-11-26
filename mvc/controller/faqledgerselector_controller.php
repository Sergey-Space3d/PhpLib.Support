<?php
// Copyright 2005-2022 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** FAQ Ledger Selector Controller */
class FaqLedgerSelectorController extends SelectorController
{
    /** The constructor */
    public function __construct($action, array $args = null)
    {
        parent::__construct($action, $args, 'FaqLedger');
    }
    
    /** Returns selection name */
    public function get_sel_name() { return SELECTOR_FAQLEDGER_ID; }
    
    /** Returns array of instances */
    protected function get_items()
    {
        $id_access = $this->get_arg('id_access');
        $approved_only = $this->get_arg('approved_only');
        return FaqLedgerMan::Instance()->get_by_access($id_access, $approved_only);
    }
}
?>