<?php
/**
 * Created by IntelliJ IDEA.
 * User: felix.reinhardt
 * Date: 2018-12-12
 * Time: 16:14
 */
function getInputFeedback($msg, $valid = false) {
    $el = "";
    $v = ($valid) ? "" : "in";
    if (strlen(trim($msg)) > 0) {
        $el = "<div class=\"".$v."valid-feedback\">";
        $el .= $msg;
        $el .= "</div>";
    }
    return $el;
}