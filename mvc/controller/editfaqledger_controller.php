<?php
// Copyright 2005-2022 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Edit FAQ Ledger Controller */
class EditFaqLedgerController extends CController
{
    /** Initialize the form */
    protected function initialize()
    {
        $id = (int)$this->get_arg('id');
        $ledger = FaqLedgerMan::Instance()->get_ledger($id);
        
        $this->set_arg('name', $this->choose_value($ledger->get_name(), 'name'));
        $desc = implode("\n", explode('<br/>', $ledger->get_desc()));
        $this->set_arg('desc', $this->choose_value($desc, 'desc'));
        $this->set_arg('context_id', $this->choose_value($ledger->get_context_id(), 'context_id'));
        $this->set_arg('priority', $this->choose_value($ledger->get_priority(), 'priority'));
        $this->set_arg('maxlength', 255); //limit description
        
        // Make priority list
        static $priorities = null;
        if (!$priorities)
        {
            $priorities = array();
            for ($i = 1; $i < 100; $i++)$priorities[$i] = $i;
        }
        
        $this->set_arg('priorities', $priorities);
        
        // Compose access's name
        $id_access = (int)$this->get_arg('id_access');
        
        if ($id_access)
        {
            $access = AccessMan::Instance()->get_ledger($id_access);
            $access_name = $access->get_name();
            if (strlen($access_name) > 24) $access_name = substr($access_name, 0, 24).'...';
            $this->set_arg('access_name', $access_name);
        }
        
        $this->enable_post(true);
    }
    
    /** Process the form */
    protected function process()
    {
        $id = (int)$this->get_value('id');
        $id_access = (int)$this->get_value('id_access');
        
        $name = $this->get_value('name');
        $desc = $this->get_value('desc');
        $context_id = $this->get_value('context_id');
        $priority = (int)$this->get_value('priority');
        
        if (!$name || strlen($name) > 64)
        {
            CHtmlForm::set_error('Subject should be between 1 and 64 characters', 'name');
        }

        $errors = CHtmlForm::get_errors();
        
        if ($errors)
        {
            CHtmlPage::set_last_error(implode('<br/>', $errors));
            return false;
        }
        
        if ($desc) $desc = implode('<br/>', explode("\n", $desc));
        
        $label = FaqLedger::label();
        $ledger = FaqLedgerMan::Instance()->get_ledger($id);
        
        $ledger->set_name($name);
        $ledger->set_desc($desc);
        $ledger->set_context_id($context_id);
        $ledger->set_priority($priority);
        
        if (!$id) 
        {
            $ledger->set_access_id($id_access);
            $ledger->set_flag(CDbRecord::HIDDEN, true);
        }
        
        if (!$ledger->write())
        {
            CHtmlPage::set_last_error("Failed to save {$label} '{$name}'");
            return false;
        }
        
        $action = $id ? 'updated' : 'added';
        CHtmlPage::set_last_info("{$label} '{$name}' was {$action}");
        return true;
    }
}
?>