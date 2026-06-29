<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Toggle FAQ Approval Controller */
class ToggleFaqApprovalController extends CController
{
    /** Initialize the form */
    protected function initialize()
    {
        $id = (int)$this->get_arg('id');
        
        $faq = new Faq($id);
        $approved = $faq->has_flag(CDbRecord::HIDDEN);
        $this->set_arg('approved', $approved);
        
        $this->enable_post(true);
    }
    
    /** Process the form */
    protected function process()
    {
        $id = (int)$this->get_value('id');
        
        $faq = new Faq($id);
        $name = $faq->get_name();
        
        $approved = $faq->has_flag(CDbRecord::HIDDEN);
        $faq->set_flag(CDbRecord::HIDDEN, !$approved, true);
        
        $state = $approved ? 'approved' : 'disapproved';
        CHtmlPage::set_last_info(Faq::label()." '{$name}' was {$state}");
        return true;
    }
}
?>