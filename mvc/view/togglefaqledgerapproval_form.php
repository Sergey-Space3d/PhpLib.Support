<?php

/** Toggle FAQ Ledger Approval Form */
class ToggleFaqLedgerApprovalForm extends CForm
{
    /** Initialize form contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        
        $this->add_inner(new CHtmlHidden('id', $id));
        
        $title = $approved ? 'Approve' : 'Disapprove';
        $ctrl = new CHtmlSubmit($title, array('class'=>$this->get_attr('class')));
        $ctrl->disable_on_click();
        $this->add_inner($ctrl);
    }
}
?>