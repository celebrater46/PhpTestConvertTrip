<?php

function hitoricap( $name ) {
    if ( ( $index = strpos($name, '#') ) === false || strlen($name) > $index + 1 )
        return str_replace( '◆', '◇', $name );
    $name = str_replace( '◆', '◇', substr($name, 0, $index) );
    $tripkey = mb_convert_encoding(substr($name, $index + 1), 'SJIS', 'UTF-8');
    if ( strlen($tripkey) >= 12 ) {
        if ( $tripkey[0] === '#' ) { // 10 digits new protocol
            if ( preg_match( '|^#([0-9a-fA-F]{16})([./0-9a-zA-Z]{0,2})$|', $tripkey, $matches ) ) {
                $key = pack('H*', $matches[1]);
                if ( ( $index = strpos($key, chr(128)) ) !== false )
                    $key = substr($key, 0, $index);
                $trip = substr(crypt($key, substr($matches[2].'..', 0, 2)), -10);
            } else
                $trip = '???';
        } elseif ( $tripkey[0] === '$' ) { // reserved
            $trip = '???';
        } else // 12 digits
            $trip = str_replace('+', '.', substr(base64_encode(sha1($tripkey, true)), 0, 12));
    } else { // 10 digits
        $key = htmlspecialchars($tripkey, ENT_QUOTES, 'SJIS');
        $salt = preg_replace( '/[^.-z]/', '.', substr($key.'H.', 1, 2) );
        $map = array(':'=>'A', ';'=>'B', '<'=>'C', '='=>'D', '>'=>'E', '?'=>'F', '@'=>'G', '['=>'a', '\\'=>'b', ']'=>'c', '^'=>'d', '_'=>'e', '`'=>'f');
        $trip = substr(crypt($key, strtr($salt, $map)), -10);
    }
    return $name.'◆'.$trip;
}