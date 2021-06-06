<?php
function HashClave(string $clave)
{
    return hash("sha1", $clave);
}
