<?php
function GenerarCodigoAlfanumerico()
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($chars), 0, 5);
}
