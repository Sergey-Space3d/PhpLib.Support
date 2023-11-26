<?php
// Copyright 2005-2022 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** FAQ Contents View */
class FaqContentsView extends CView
{
    /** Initialize view contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        
        if (!CHtmlStyle::has_selector('.faq_wrap'))
        {
            $style_el = new CHtmlStyle();
            $this->add_inner($style_el);
            
            $style_el->add_selector('.faq_wrap', 'overflow-wrap:break-word;word-wrap:break-word;word-break:break-word;');
            $style_el->add_selector('.faq_ledger', 'font-weight:bold;color:#555555;font-size:135%;');
            $style_el->add_selector('.faq_name', 'font-weight:bold;color:#555555;margin-left:20px;');
            $style_el->add_selector('.faq_desc', 'margin-left:20px;text-align:justify;');
            $style_el->add_selector('.faq_link', 'color:black;');
        }
        
        $table = new CHtmlTable(array('width'=>'100%'));
        $this->add_inner($table);

        $table->add_row($this->make_faq_toc($ledgers), array('style'=>'padding-bottom:16px;'));
        $table->add_row($this->make_faq_list($ledgers), array('style'=>'padding-bottom:16px;'));
    }
    
    /** Returns FAQ TOC */
    protected function make_faq_toc(array $ledgers)
    {
        $arr = array();
        
        foreach ($ledgers as $ledger)
        {
            $faqs = FaqLedgerMan::Instance()->get_faqs($ledger, true);
            if ($faqs) foreach ($faqs as $faq) $arr["#faq{$faq->get_id()}"] = $faq->get_name();
        }
        
        if (count($arr) > 1)
        {
            $p = new CHtmlElement('p');
            $ul = new CHtmlElement('ul');
            $p->add_inner($ul);

            foreach ($arr as $href=>$name)
            {
                $a = new CHtmlA($href, array('class'=>'faq_link faq_wrap'), $name);
                $ul->add_inner(new CHtmlElement('li', null, $a));
            }
            
            return $p;
        }
        
        return null;
    }
    
    /** Returns FAQ list */
    protected function make_faq_list(array $ledgers)
    {
        $div = new CHtmlElement('div');
        
        foreach ($ledgers as $ledger)
        {
            $p = new CHtmlElement('p', array('class'=>'faq_ledger faq_wrap'), $ledger->get_name());
            $div->add_inner($p);
            $desc = FaqLedgerMan::Instance()->populate_contents($ledger->get_desc());
            if ($desc) $div->add_inner(new CHtmlElement('p', array('class'=>'faq_desc faq_wrap'), $desc));
            
            $faqs = FaqLedgerMan::Instance()->get_faqs($ledger, true);
            
            if ($faqs) foreach ($faqs as $faq)
            {
                $attrs = array('id'=>"faq{$faq->get_id()}", 'class'=>'faq_name faq_wrap');
                $div->add_inner(new CHtmlElement('div', $attrs, $faq->get_name()));
                $desc = FaqLedgerMan::Instance()->populate_contents($faq->get_desc());
                $div->add_inner(new CHtmlElement('p', array('class'=>'faq_desc faq_wrap'), $desc));
            }
        }
        
        return $div;
    }
}
?>