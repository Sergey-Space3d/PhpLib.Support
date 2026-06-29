<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Edit FAQ Ledger Form */
class EditFaqLedgerForm extends FormLayout
{
    /** Initialize form contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        $is_new = $id == 0;
        
        $this->add_inner(new CHtmlHidden('id', $id));
        $this->add_inner(new CHtmlHidden('id_access', $id_access));
        
        $label = FaqLedger::label();
        $title = $is_new ? "Add {$label}" : "Edit {$label}";
        if ($access_name) $title = "$title ({$access_name})";
        $this->set_title('<b>'.$title.'</b>');

        $this->add_control('Name', new CHtmlText('name', $name), true, true);
        $this->add_control('Description', new CHtmlTextArea('desc', $desc, 7, 40, array('maxlength'=>$maxlength)));
        $this->add_control('Context Id', new CHtmlText('context_id', $context_id));
        
        $ctrl = new CHtmlCbox('priority', $priority);
        $ctrl->add_items($priorities);
        $this->add_control('Priority', $ctrl);
        
        $ctrl = new CHtmlSubmit('Submit');
        $ctrl->disable_on_click();
        $this->add_control_deck($ctrl);
    }
}
?>