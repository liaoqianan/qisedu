<?php
//表情转字符串
function emojiEncode($content) {
    return json_decode(preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function($str) {
        return addslashes($str[0]);
    }, json_encode($content)));
}
//字符串转表情
function emojiDecode($content) {
    return json_decode(preg_replace_callback('/\\\\\\\\/i', function() {
        return '\\';
    }, json_encode($content)));
}