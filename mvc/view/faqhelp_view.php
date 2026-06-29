<?php
// Copyright 2005-2026 Sergey Shustov.
// License: GPL v2.  See License.txt.

/** FAQ Help View */
class FaqHelpView extends CView
{
    /** Initialize view contents */
    protected function init_contents(array $args)
    {
        @extract($args);
        $ledgers = FaqLedgerMan::Instance()->get_by_access($access, true, $context_id);
        
        if ($ledgers)
        {
            if (!CHtmlStyle::has_selector('.help_button'))
            {
                $style_el = new CHtmlStyle();
                $this->add_inner($style_el);
    
                $style = 'position:fixed;top:140px;right:10px;width:30px;height:30px;z-index:10;';
                $style .= 'border:4px solid grey;border-radius:19px;border-spacing:0px;cursor: pointer;';
                $style .= 'color:grey;font-size:28px;font-weight:bold;text-align:center;vertical-align:middle;';
                $style_el->add_selector('.help_button', $style);
    
                $style = 'position:fixed;top:135px;right:5px;width:600px;max-height:500px;z-index:9;';
                $style .= 'border:1px solid grey;box-shadow: 0 0 3px 3px grey;overflow:auto;';
                $style .= 'background-color:white;padding:30px;display:none;';
                $style_el->add_selector('.help_window', $style);
            }
            
            $button = new CHtmlElement('div', array('class'=>'help_button', 'onclick'=>'ToggleHelpWindow()'), '?');
            $this->add_inner($button);

            $contents = CDispatcher::instance()->get_view('FaqContents', array('ledgers'=>$ledgers));
            $window = new CHtmlElement('div', array('class'=>'help_window'), $contents);
            $this->add_inner($window);

            $button_id = $button->get_id(true);
            $window_id = $window->get_id(true);

            $js = new CHtmlJavaScript();
            $this->add_inner($js);

            $lines = array(
                "var btn = document.getElementById('{$button_id}');",
                "var win = document.getElementById('{$window_id}');",
                "if (win.style.display != 'block') {",
                "btn.innerHTML = 'X';",
                "btn.style.right = '25px';",
                "win.style.display = 'block';",
                "}",
                "else {",
                "btn.innerHTML = '?';",
                "btn.style.right = '10px';",
                "win.style.display = 'none';",
                "}",
            );
            $js->add_function('ToggleHelpWindow', $lines);
        }
    }
}
?>