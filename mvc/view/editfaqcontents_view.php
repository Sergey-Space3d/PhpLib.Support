<?php
// Copyright 2005-2022 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** Edit FAQ Contents View */
class EditFaqContentsView extends CView
{
    protected static $classes = array();
    protected $accesses;
    protected $permissions;
    
    /** Initialize view contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        
        $this->accesses = $accesses;
        $this->permissions = $permissions;
        
        if ($this->accesses)
        {
            if (!CHtmlStyle::has_selector('.editfaq_tbar'))
            {
                $style_el = new CHtmlStyle();
                $this->add_inner($style_el);
                
                $style_el->add_selector('.editfaq_tbar', 'padding:4px;');
                $style_el->add_selector('.editfaq_tb_selector', 'display:inline;margin:0px 4px;');
                $style_el->add_selector('.editfaq_tb_button', 'display:inline;margin:0px 10px;float:right;');
                
                self::$classes['toolbar'] = $this->get_css_class('toolbar', 'editfaq_tbar');
                self::$classes['toolbar_button'] = $this->get_css_class('toolbar_button', 'editfaq_tb_button');
                self::$classes['button'] = $this->get_css_class('button');
                self::$classes['table'] = $this->get_css_class('table');
                self::$classes['header'] = $this->get_css_class('header');
                self::$classes['column_names'] = $this->get_css_class('column_names');
            }
            
            $table = new CHtmlTable(array('width'=>'100%'));
            $this->add_inner($table);
            
            $td_attrs = array('style'=>'padding-bottom:16px;');
            $table->add_row($this->make_toolbar(), $td_attrs);
            
            $id_access = CHtmlForm::get_value(SELECTOR_ACCESS_ID);
            $id_ledger = CHtmlForm::get_value(SELECTOR_FAQLEDGER_ID);
            
            $ledgers = $id_ledger ?
            array(FaqLedgerMan::Instance()->get_ledger($id_ledger)) :
            FaqLedgerMan::Instance()->get_by_access($id_access, false);
            
            if ($ledgers)
            {
                $table->add_row($this->make_ledger_list($ledgers), $td_attrs);
                $table->add_row($this->make_faq_list($ledgers), $td_attrs);
            }
        }
    }
    
    /** Make toolbar element */
    protected function make_toolbar()
    {
        // Create the toolbar (on top)  
        $table = new CHtmlTable(array('class'=>self::$classes['toolbar']));
        $items = array();
        
        $div = new CHtmlElement('div');
        $items[] = $div;
        
        $args = array('accesses'=>$this->accesses, 'title'=>'FAQ Access');
        $asel = CDispatcher::instance()->get_form("FaqAccessSelector", $args, array('style'=>'float:left;'));
        $div->add_inner($asel);
        
        $id_access = CHtmlForm::get_value(SELECTOR_ACCESS_ID, true, true);
        $args = array('id_access'=>$id_access, 'all'=>true, 'approved_only'=>false);
        $fsel = CDispatcher::instance()->get_form("FaqLedgerSelector", $args, array('style'=>'float:left;'));
        $div->add_inner($fsel);
        
        if ($this->permissions['add'])
        {
            $form = CDispatcher::instance()->get_form("EditFaqLedger", array('id'=>0, 'id_access'=>$id_access), array('style'=>'float:right;'));
            $collapsed = new CollapsedForm($form, 'Add '.FaqLedger::label(), array('class'=>self::$classes['toolbar_button']), false);
            CollapsedForm::init_display($collapsed, array($asel, $fsel), true);
            $items[] = $collapsed;
        }
        
        $table->add_row($items);
        return $table;
    }
    
    /** Make ledger list */
    protected function make_ledger_list(array $ledgers)
    {
        $list_class = self::$classes['table'];
        $table = new CHtmlTable(array('class'=>$list_class, 'style'=>'width:100%;'));
        
        $title_attrs = array('colspan'=>'100%', 'class'=>self::$classes['header']);
        $table->add_row(FaqLedger::label()->camel(true), $title_attrs);
        
        $table->add_row(array('Name', 'Action'), array('class'=>self::$classes['column_names'], 'align'=>'center'));
        $table->set_column_attrs(0, array('class'=>$list_class));
        $table->set_column_attrs(1, array('class'=>$list_class, 'style'=>'width:20%;'));
        
        foreach ($ledgers as $ledger)
        {
            $name = $ledger->get_name();
            $deck = $this->make_ledger_deck_td($ledger);
            $table->add_row(array($name, $deck), array('class'=>$list_class));
        }
        
        return $table;
    }
    
    /** Make deck element for the ledger */
    protected function make_ledger_deck_td(FaqLedger $ledger)
    {
        $form_view = new FormView();
        $forms = array();
        
        $id_access = CHtmlForm::get_value(SELECTOR_ACCESS_ID);
        $args = array('id'=>$ledger->get_id(), 'id_access'=>$id_access);
        $btn_attrs = isset(self::$classes['button']) ? array('class'=>self::$classes['button']) : null;
        
        if ($this->permissions['block'])
        {
            $forms['Approval'] = CDispatcher::instance()->get_form("ToggleFaqLedgerApproval", $args, $btn_attrs);
            $form_view->add_form($forms['Approval']);
        }
        
        if ($this->permissions['delete'])
        {
            $forms['Delete'] = CDispatcher::instance()->get_form("DeleteFaqLedger", $args, $btn_attrs);
            $form_view->add_form($forms['Delete']);
        }
        
        if ($this->permissions['edit'])
        {
            $form = CDispatcher::instance()->get_form("EditFaqLedger", $args);
            $forms['Edit'] = new CollapsedForm($form, 'Edit', $btn_attrs, false);
            $form_view->add_form(new CHtmlElement('td', null, $forms['Edit']));
        }
        
        if ($this->permissions['add'])
        {
            $args = array('id'=>0, 'id_ledger'=>$ledger->get_id());
            $form = CDispatcher::instance()->get_form("EditFaq", $args);
            $forms['AddFaq'] = new CollapsedForm($form, 'Add '.Faq::label(), $btn_attrs, false);
            $form_view->add_form(new CHtmlElement('td', null, $forms['AddFaq']));
        }
        
        CollapsedForm::init_display($forms['Edit'], array($forms['Approval'], $forms['Delete'], $forms['AddFaq']), true);
        CollapsedForm::init_display($forms['AddFaq'], array($forms['Approval'], $forms['Delete'], $forms['Edit']), true);
        return $form_view;
    }
    
    /** Make FAQ list */
    protected function make_faq_list(array $ledgers)
    {
        $list_class = self::$classes['table'];
        $table = new CHtmlTable(array('class'=>$list_class, 'style'=>'width:100%;'));
        
        $title_attrs = array('colspan'=>'100%', 'class'=>self::$classes['header']);
        $table->add_row(Faq::label()->camel(true), $title_attrs);
        
        $table->add_row(array(FaqLedger::label(), 'Name', 'Action'), array('class'=>self::$classes['column_names'], 'align'=>'center'));
        $table->set_column_attrs(array(0, 1), array('class'=>$list_class));
        $table->set_column_attrs(2, array('class'=>$list_class, 'style'=>'width:20%;'));
        
        foreach ($ledgers as $ledger)
        {
            $faqs = FaqLedgerMan::Instance()->get_faqs($ledger, false);
            
            if ($faqs)
            {
                $rowspan = count($faqs);
                $faq = array_shift($faqs);
                
                $ledger_td = $table->make_row_td(0, $ledger->get_name(), array('rowspan'=>$rowspan));
                $table->add_row(array($ledger_td, $faq->get_name(), $this->make_faq_deck_td($ledger, $faq)));
                
                foreach ($faqs as $faq)
                {
                    $table->add_row(array($faq->get_name(), $this->make_faq_deck_td($ledger, $faq)));
                }
            }
        }
        
        return $table;
    }
    
    /** Make deck element for the FAQ */
    protected function make_faq_deck_td(FaqLedger $ledger, Faq $faq)
    {
        $form_view = new FormView();
        $forms = array();
        
        $args = array('id'=>$faq->get_id(), 'id_ledger'=>$ledger->get_id());
        $btn_attrs = isset(self::$classes['button']) ? array('class'=>self::$classes['button']) : null;
        
        if ($this->permissions['block'])
        {
            $forms['Approval'] = CDispatcher::instance()->get_form("ToggleFaqApproval", $args, $btn_attrs);
            $form_view->add_form($forms['Approval']);
        }
        
        if ($this->permissions['delete'])
        {
            $forms['Delete'] = CDispatcher::instance()->get_form("DeleteFaq", $args, $btn_attrs);
            $form_view->add_form($forms['Delete']);
        }
        
        if ($this->permissions['edit'])
        {
            $form = CDispatcher::instance()->get_form("EditFaq", $args);
            $forms['Edit'] = new CollapsedForm($form, 'Edit', $btn_attrs, false);
            $form_view->add_form(new CHtmlElement('td', null, $forms['Edit']));
        }
        
        CollapsedForm::init_display($forms['Edit'], array($forms['Delete'], $forms['Approval']), true);
        return $form_view;
    }
}
?>