<?php
/*
 * Created by IntelliJ IDEA.
 * User: felix.reinhardt
 * Date: 2018-12-12
 * Time: 16:14
 */

/**
 * @param string $new The new password
 * @param null|string $confirmation The confirmation to match the new password
 * @return bool Password import is valid or not
 * @throws Exception
 */
function validPasswordInput($new, $confirmation = null)
{
    if (strlen($new) < 6) {
        throw new Exception("Password must have 6 characters at least.");
    } else if (!is_null($confirmation) && $new !== $confirmation) {
        throw new Exception("The password confirmation does not match.");
    }
    return true;
}