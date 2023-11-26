<?php

/** Edit Message Template Controller */
class EditMessageTemplateController extends CController
{
    /** Initialize the form */
    protected function initialize()
    {
        $id = (int)$this->get_arg('id');
        $tm = new MessageTemplate($id);
        
        $this->set_arg('category', $this->choose_value($tm->get_category(), 'category'));
        $this->set_arg('title', $this->choose_value($tm->get_title(), 'title'));
        $this->set_arg('contents', $this->choose_value($tm->get_contents(), 'contents'));
        
        $this->set_arg('send_mail', $this->choose_value($tm->has_flag(MessageTemplate::SEND_MAIL), 'send_mail'));
        $this->set_arg('send_sms', $this->choose_value($tm->has_flag(MessageTemplate::SEND_SMS), 'send_sms'));
        $this->set_arg('queue', $this->choose_value($tm->has_flag(MessageTemplate::DELIVER_IN_QUEUE), 'queue'));
        $this->set_arg('maxlength', 10000);
        $this->set_arg('sms_maxlength', 142);
        
        $this->enable_post(true);
    }
    
    /** Process the form */
    protected function process()
    {
        $id = (int)$this->get_value('id');
        $title = $this->get_value('title');
        $contents = $this->get_value('contents');
        $send_mail = (bool)$this->get_value('send_mail');
        $send_sms = (bool)$this->get_value('send_sms');
        $queue = (bool)$this->get_value('queue');
        
        if (!$title || strlen($title) > 64)
        {
            CHtmlForm::set_error('Title should be between 1 and 64 characters', 'title');
        }
        
        if (!$contents || strlen($contents) > 10000)
        {
            CHtmlForm::set_error('Contents should be between 1 and 10000 characters', 'contents');
        }
        
        $errors = CHtmlForm::get_errors();
        
        if ($errors)
        {
            CHtmlPage::set_last_error(implode('<br/>', $errors));
            return false;
        }

        $tm = new MessageTemplate($id);
        $tm->set_title($title);
        $tm->set_contents($contents);
        
        $tm->set_flag(MessageTemplate::SEND_MAIL, $send_mail);
        $tm->set_flag(MessageTemplate::SEND_SMS, $send_sms);
        $tm->set_flag(MessageTemplate::DELIVER_IN_QUEUE, $queue);
        
        if (!$tm->write())
        {
            CHtmlPage::set_last_error("Failed to save notification '{$tm->get_category()}'");
            return false;
        }
        
        CHtmlPage::set_last_info("Notification '{$tm->get_category()}' was updated");
        return true;
    }
}
?>