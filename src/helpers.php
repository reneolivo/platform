<?php

/**
 * Sorts an array of associative arrays or objects by field
 * @param string $field
 * @param array $arr
 * @param int $sorting
 * @param boolean $case_insensitive
 * @return boolean 
 */
function array_sortby($field, &$arr, $sorting = SORT_ASC, $case_insensitive = true) {
    if (is_array($arr) && (count($arr) > 0)) {
        if ($case_insensitive == true) {
            $strcmp_fn = 'strnatcasecmp';
        } else {
            $strcmp_fn = 'strnatcmp';
        }
        if ($sorting == SORT_ASC) {
            $fn = function($a, $b) use ($field) {
                if (is_object($a) && is_object($b) && isset($a->$field) && isset($b->$field)) {
                    return call_user_func($strcmp_fn, $a->$field, $b->$field);
                } else if (is_array($a) && is_array($b) && isset($a[$field]) && isset($b[$field])) {
                    return call_user_func($strcmp_fn, $a[$field], $b[$field]);
                } else {
                    return 0;
                }
            };
        } else {
            $fn = function($a, $b) use ($field) {
                if (is_object($a) && is_object($b) && isset($a->$field) && isset($b->$field)) {
                    return call_user_func($strcmp_fn, $b->$field, $a->$field);
                } else if (is_array($a) && is_array($b) && isset($a[$field]) && isset($b[$field])) {
                    return call_user_func($strcmp_fn, $b[$field], $a[$field]);
                } else {
                    return 0;
                }
            };
        }
        usort($arr, $fn);
        return true;
    } else {
        return false;
    }
}

/**
 * Base64 encode (Binary to ASCII or btoa in javascript)
 * @param string $data
 * @param bool $url_safe
 * @return string The base64 ASCII string
 */
function base64_encode_safe($data, $url_safe = false) {
    $data = base64_encode($data);
    if ($url_safe) {
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    }
    return $data;
}

/**
 * Base64 decode (ASCII to binary or atob in javascript)
 * @param string $data String encoded using str::base64encode()
 * @param bool $url_safe
 * @return string The binary string
 */
function base64_decode_safe($data, $url_safe = false) {
    if ($url_safe) {
        $data = str_replace(array('-', '_'), array('+', '/'), $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
    }
    return base64_decode($data);
}

function str_is_regex($str) {
    return is_string($str) and ( (preg_match('/^\/.*\/[imsxeADSUXJu]*$/', $str)) > 0);
}

function str_is_json($str) {
    return is_string($str) and is_object(@json_decode($str, false, 1));
}

function str_is_html($str) {
    return is_string($str) and ( preg_match('/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)\/?>/i', $str) > 0);
}
