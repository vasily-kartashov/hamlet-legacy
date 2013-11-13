<?php
function smarty_modifier_htmljson($value)
{
    return htmlentities(json_encode($value));
}
