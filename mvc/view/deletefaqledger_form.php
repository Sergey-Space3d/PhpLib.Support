<?php

/** Delete FAQ Ledger Form */
class DeleteFaqLedgerForm extends CForm
{
    /** Initialize form contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        
        $this->add_inner(new CHtmlHidden('id', $id));
        
        $label = FaqLedger::label()->lower();
        $this->set_confirm("Confirm deleting the {$label}");

        $ctrl = new CHtmlSubmit('Delete', array('class'=>$this->get_attr('class')));
        $ctrl->disable_on_click();
        $this->add_inner($ctrl);
    }
}
?>