<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Edit FAQ Form */
class EditFaqForm extends FormLayout
{
    /** Initialize form contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        $is_new = $id == 0;
        
        $this->add_inner(new CHtmlHidden('id', $id));
        $this->add_inner(new CHtmlHidden('id_ledger', $id_ledger));
        
        $label = Faq::label();
        $title = $is_new ? "Add {$label}" : "Edit {$label}";
        
        if ($ledger_name)
        {
            $ledger_label = FaqLedger::label();
            $title = "$title ({$ledger_name} {$ledger_label})";
        }
        
        $this->set_title('<b>'.$title.'</b>');
        
        $this->add_control('Subject', new CHtmlText('name', $name), true, true);
        $this->add_control('Contents', new CHtmlTextArea('desc', $desc, 10, 60, array('maxlength'=>$maxlength)), true, true);
        
        $ctrl = new CHtmlCbox('priority', $priority);
        $ctrl->add_items($priorities);
        $this->add_control('Priority', $ctrl);
        
        $ctrl = new CHtmlSubmit('Submit');
        $ctrl->disable_on_click();
        $this->add_control_deck($ctrl);
    }
}
?>