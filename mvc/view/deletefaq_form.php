<?php

/** Delete FAQ Form */
class DeleteFaqForm extends CForm
{
    /** Initialize form contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        
        $this->add_inner(new CHtmlHidden('id', $id));
        
        $label = Faq::label();
        $this->set_confirm("Confirm deleting the {$label}");

        $ctrl = new CHtmlSubmit('Delete', array('class'=>$this->get_attr('class')));
        $ctrl->disable_on_click();
        $this->add_inner($ctrl);
    }
}
?>