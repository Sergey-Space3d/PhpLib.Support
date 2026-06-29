<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** FAQ Access Selector Controller */
class FaqAccessSelectorController extends SelectorController
{
    /** The constructor */
    public function __construct($action, array $args = null)
    {
        parent::__construct($action, $args, 'CDbLedger');
    }
    
    /** Returns selection name */
    public function get_sel_name() { return SELECTOR_ACCESS_ID; }
    
    /** Returns array of instances */
    protected function get_items()
    {
        return $this->get_arg('accesses');
    }
}
?>