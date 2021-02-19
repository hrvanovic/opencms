<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller;

global $core;
$core->requireMainController();

class ErrorController
{
    private $data = array(
        "header" => null,
        "links" => array("font/vt323", "error")
    );

    /**
     * Prikazuje 404 stranicu.
     */
    function error_404()
    {
        global $core;
        $core->pageRender("Error/error_404", $this->data);
    }

    function error_403()
    {
        global $core;
        $core->pageRender("Error/error_505", $this->data);
    }
    /**
     * Prikazuje 505 stranicu.
     */
    function error_505()
    {
        global $core;
        $core->pageRender("Error/error_505", $this->data);
    }

    function error_505_no_template()
    {
        global $core;
        $core->pageRender("Error/error_505", array(), false, false);
    }
}
