<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** FAQ Ledger Manager Class */
class FaqLedgerMan extends CDbLedgerMan
{
    /** The constructor */
    protected function __construct()
    {
        $table_name = SupportDb::get_name().'.faqledgers';
        $map_table_name = SupportDb::get_name().'.faqledger_to_faq';
        
        parent::__construct($table_name, $map_table_name, 'Faq', 'FaqLedger');
        $this->m_sort_by = 'access_id,_priority';
    }
    
    /** Get ledgers by access (may be id, or string) */
    public function get_by_access($access, $approved_only, $context_id = null, $return_instances = true)
    {
        $access_id = is_numeric($access) ? $access : AccessMan::Instance()->get_ledger_by_name($access);
        $condition = "access_id={$access_id} OR access_id=0";
        if ($context_id) $condition = "({$condition}) AND context_id='{$context_id}'";
        if ($approved_only) $condition = "({$condition}) AND (flags & ".CDbRecord::HIDDEN.")=0";
        
        $result = $this->m_table->get($condition, '_priority', !$return_instances);
        return $return_instances ? $this->to_instances($result) : $this->to_ids($result);
    }
    
    /** Inquire if access ledgers exist (access can be id, or string) */
    public function has_access($access)
    {
        $access_id = is_numeric($access) ? $access : AccessMan::Instance()->get_ledger_by_name($access);
        return $this->m_table->has("access_id={$access_id} AND (flags & ".CDbRecord::HIDDEN.")=0");
    }
    
    /** Add FAQ to ledger (arguments may be ids, or instances) */
    public function add_faq($faq, $ledger)
    {
        $this->map($ledger, $faq);
    }
    
    /** Remove FAQ from ledger (arguments may be ids, or instances) */
    public function remove_faq($faq, $ledger)
    {
        $this->unmap($ledger, $faq);
    }
    
    /** Get ledger FAQs. The ledger can be id, or instance */
    public function get_faqs($ledger, $approved_only, $return_instances = true)
    {
        $ret = $this->get_by_ledger($ledger, true);
        
        if ($ret && $approved_only)
        {
            $filtered = array();
            
            foreach ($ret as $id=>$f)
            {
                if (!$f->has_flag(CDbRecord::HIDDEN))
                {
                    $filtered[$id] = $f;
                }
            }
            
            $ret = $filtered;
        }
        
        if ($ret)
        {
            $func = function($a, $b)
            {
                $a = $a->get_priority();
                $b = $b->get_priority();
                
                if ($a == $b)return 0;
                return ($a < $b) ? -1 : 1;
            };
            
            uasort($ret, $func);
            
            if (!$return_instances) foreach ($ret as $id=>&$val)
            {
                $val = $id;
            }
        }
        
        return $ret;
    }
    
    /** Populates message contents, substituting field placeholders by real values. Returns array of text lines */
    public function populate_contents($contents)
    {
        // TODO
        return $contents;
    }
}
?>