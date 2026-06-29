<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Defines FAQ ledger */
class FaqLedger extends CDbLedger
{
    private static $m_flfd = array(
    'access_id'=>'int', 
    'context_id'=>'varchar(32)',
    '_priority'=>'int', 
    '_desc'=>'mediumblob');
    
    /** Returns field definitions for the table */
    public function get_field_def()
    {
        return array_merge(parent::get_field_def(), self::$m_flfd);
    }
    
    /** Called when writing new record to the database */
    protected function on_write_new()
    {
        parent::on_write_new();
        
        $this->field_to_int('access_id');
        $this->field_to_int('_priority');
    }
    
    /** Returns duplicate condition */
    public function get_duplicate_condition()
    {
        $condition = parent::get_duplicate_condition();
        $condition .= " AND access_id={$this->m_fields['access_id']}";
        return $condition;
    }
    
    public function get_access_id() { return $this->m_fields['access_id']; }
    public function get_context_id() { return $this->m_fields['context_id']; }
    public function get_priority() { return $this->m_fields['_priority']; }
    
    public function set_access_id($arg, $save = false) { $this->set_field('access_id', (int)$arg, $save); }
    public function set_context_id($arg, $save = false) { $this->set_field('context_id', $arg, $save); }
    public function set_priority($arg, $save = false) { $this->set_field('_priority', (int)$arg, $save); }
}
?>