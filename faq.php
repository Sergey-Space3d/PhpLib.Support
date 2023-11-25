<?php
// Copyright 2005-2022 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** FAQ Class */
class Faq extends CDbRecord
{
    private static $m_faqfd = array(
    '_name'=>'varchar(64)', 
    '_desc'=>'mediumblob',
    '_priority'=>'int');
    
    /** The constructor */
    public function __construct($id = 0)
    {
        parent::__construct(SupportDb::get_name().'.faqs', $id);
    }
    
    /** Returns field definitions for the table */
    public function get_field_def()
    {
        return self::$m_faqfd;
    }
    
    /** Called when writing new record to the database */
    protected function on_write_new() 
    {
        $this->field_to_int('_priority');
    }
    
    /** Returns true if the record is valid */
    public function verify()
    {
        return $this->m_fields['_name'] && $this->m_fields['_desc'];
    }
    
    public function get_name() { return $this->m_fields['_name']; }
    public function get_desc() { return $this->m_fields['_desc']; }
    public function get_priority() { return $this->m_fields['_priority']; }
    
    public function set_name($arg, $save = false) { $this->set_field('_name', $arg, $save); }
    public function set_desc($arg, $save = false) { $this->set_field('_desc', $arg, $save); }
    public function set_priority($arg, $save = false) { $this->set_field('_priority', (int)$arg, $save); }
}
?>