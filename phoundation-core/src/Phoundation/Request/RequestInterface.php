<?php
namespace Phoundation\Request;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getMethod();
}