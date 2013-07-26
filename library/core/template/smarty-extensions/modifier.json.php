<?php
function smarty_modifier_json($value)
{
    return htmlentities(json_encode($value));
}
