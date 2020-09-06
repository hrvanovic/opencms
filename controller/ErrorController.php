<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function error_404() {
    global $core;
    $core->pageRender("Error/error_404");
}

function error_505() {
    global $core;
    $core->pageRender("Error/error_505");
}
    