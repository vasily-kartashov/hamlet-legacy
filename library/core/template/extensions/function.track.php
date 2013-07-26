<?php
function smarty_function_track($params, Smarty_Internal_Template $template)
{
    echo ' data-track="' . htmlentities(json_encode($params['data'])) . '"';
}
