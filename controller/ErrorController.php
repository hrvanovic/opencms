<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace controller;

class ErrorController
{
    function error_404()
    {
        global $core;
        $core->pageRender("ErrorView/error_404");
    }

    function error_505()
    {
        global $core;
        $core->pageRender("ErrorView/error_505");
    }

    function error_505_no_template()
    {
        global $core;
        $core->pageRender("ErrorView/error_505", array(), false, false);
    }
}