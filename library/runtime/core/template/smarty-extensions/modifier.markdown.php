<?php
use \Michelf\Markdown;

function smarty_modifier_markdown($value)
{
    return Markdown::defaultTransform($value);
}
