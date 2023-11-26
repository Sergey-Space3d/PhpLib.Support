<?php

/** Delete FAQ Controller */
class DeleteFaqController extends CController
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
        $label = Faq::label();
        
        $faq = new Faq($id);
        $name = $faq->get_name();
        
        if (!FaqMan::Instance()->delete($id))
        {
            CHtmlPage::set_last_error("Failed to delete {$label->lower()} '{$name}'");
            return false;
        }
        
        CHtmlPage::set_last_info("{$label} '{$name}' was deleted");
        return true;
    }
}
?>