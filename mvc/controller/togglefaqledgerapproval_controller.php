<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Toggle FAQ Ledger Approval Controller */
class ToggleFaqLedgerApprovalController extends CController
{
    /** Initialize the form */
    protected function initialize()
    {
        $id = (int)$this->get_arg('id');
        
        $ledger = FaqLedgerMan::Instance()->get_ledger($id);
        $approved = $ledger->has_flag(CDbRecord::HIDDEN);
        $this->set_arg('approved', $approved);
        
        $this->enable_post(true);
    }
    
    /** Process the form */
    protected function process()
    {
        $id = (int)$this->get_value('id');
        
        $ledger = FaqLedgerMan::Instance()->get_ledger($id);
        $name = $ledger->get_name();
        
        $approved = $ledger->has_flag(CDbRecord::HIDDEN);
        $ledger->set_flag(CDbRecord::HIDDEN, !$approved, true);
        
        $state = $approved ? 'approved' : 'disapproved';
        CHtmlPage::set_last_info(FaqLedger::label()." '{$name}' was {$state}");
        return true;
    }
}
?>