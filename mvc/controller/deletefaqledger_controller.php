<?php

/** Delete FAQ Ledger Controller */
class DeleteFaqLedgerController extends CController
{
    /** Initialize the form */
    protected function initialize()
    {
        $this->enable_post(true);
    }
    
    /** Process the form */
    protected function process()
    {
        $id = (int)$this->get_value('id');
        $label = FaqLedger::label();
        
        $ledger = FaqLedgerMan::Instance()->get_ledger($id);
        $name = $ledger->get_name();
        
        if (!FaqLedgerMan::Instance()->delete($id))
        {
            CHtmlPage::set_last_error("Failed to delete {$label->lower()} '{$name}'");
            return false;
        }
        
        CHtmlPage::set_last_info("{$label} {$name} was deleted");
        return true;
    }
}
?>