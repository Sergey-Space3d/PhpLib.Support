<?php
// Copyright 2005-2022 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** FAQ Manager Class */
class FaqMan extends CDbRecordManSingleton
{
    /** The constructor */
    protected function __construct()
    {
        parent::__construct(SupportDb::get_name().'.faqs', 'Faq');
        $this->m_sort_by = '_priority';
    }
    
    /** Initialize db dependencies */
    protected function init_dependencies() 
    {
        $this->add_id_reference(SupportDb::get_name().'.faqledger_to_faq', 'id2');
    }
}
?>