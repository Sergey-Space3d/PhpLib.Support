<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Edit FAQ Controller */
class EditFaqController extends CController
{
    /** Initialize the form */
    protected function initialize()
    {
        $id = (int)$this->get_arg('id');
        $faq = new Faq($id);
        
        $this->set_arg('name', $this->choose_value($faq->get_name(), 'name'));
        $desc = implode("\n", explode('<br/>', $faq->get_desc()));
        $this->set_arg('desc', $this->choose_value($desc, 'desc'));
        $this->set_arg('priority', $this->choose_value($faq->get_priority(), 'priority'));
        $this->set_arg('maxlength', 6000); //limit description
        
        // Make priority list
        static $priorities = null;
        if (!$priorities)
        {
            $priorities = array();
            for ($i = 1; $i < 100; $i++)$priorities[$i] = $i;
        }
        
        $this->set_arg('priorities', $priorities);
        
        // Compose ledger's name
        $id_ledger = (int)$this->get_arg('id_ledger');
        
        if ($id_ledger)
        {
            $ledger = FaqLedgerMan::Instance()->get_ledger($id_ledger);
            $ledger_name = $ledger->get_name();
            if (strlen($ledger_name) > 24) $ledger_name = substr($ledger_name, 0, 24).'...';
            $this->set_arg('ledger_name', $ledger_name);
        }
        
        $this->enable_post(true);
    }
    
    /** Process the form */
    protected function process()
    {
        $id = (int)$this->get_value('id');
        $id_ledger = (int)$this->get_value('id_ledger');
        $name = $this->get_value('name');
        $desc = $this->get_value('desc');
        $priority = (int)$this->get_value('priority');
        
        if (!$name || strlen($name) > 64)
        {
            CHtmlForm::set_error('Subject should be between 1 and 64 characters', 'name');
        }
        
        if (!$desc)
        {
            CHtmlForm::set_error('Contents should be entered', 'desc');
        }
        
        $errors = CHtmlForm::get_errors();
        
        if ($errors)
        {
            CHtmlPage::set_last_error(implode('<br/>', $errors));
            return false;
        }
        
        $desc = implode('<br/>', explode("\n", $desc));
        
        $label = Faq::label();
        
        $faq = new Faq($id);
        $faq->set_name($name);
        $faq->set_desc($desc);
        $faq->set_priority($priority);
        
        if (!$faq->write())
        {
            CHtmlPage::set_last_error("Failed to save {$label} '{$name}'");
            return false;
        }
        
        if (!$id)
        {
            $faq->set_flag(CDbRecord::HIDDEN, true, true);
            FaqLedgerMan::Instance()->add_faq($faq, $id_ledger);
        }
        
        $action = $id ? 'updated' : 'added';
        CHtmlPage::set_last_info("{$label} '{$name}' was {$action}");
        return true;
    }
}
?>