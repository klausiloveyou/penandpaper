<?php
/**
 * Created by IntelliJ IDEA.
 * User: felix.reinhardt
 * Date: 2019-01-12
 * Time: 21:32
 */

class Constants {

    const Subpages = [
        "profile",
        "armory"
    ];

    public static function getIndexOfSubpage($subpage)
    {
        foreach (self::Subpages as $i => $sp) {
            if ($subpage === $sp) {
                return $i;
            }
        }
    }
}