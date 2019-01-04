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

function validPasswordInput($new, $confirmation = null) {
    if (strlen($new) < 6) {
        return [false, "Password must have 6 characters at least."];
    } else if (!is_null($confirmation) && $new !== $confirmation) {
        return [false, "The password confirmation does not match."];
    } else {
        return [true];
    }
}