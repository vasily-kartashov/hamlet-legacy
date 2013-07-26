<?php
namespace task\googledrive
{
    use RecursiveFilterIterator;
    class SubversionFilterIterator extends RecursiveFilterIterator
    {
        public function accept() {
            return !in_array(
                $this->current()->getFilename(),
                array(
                    '.svn',
                ),
                true
            );
        }
    }
}
