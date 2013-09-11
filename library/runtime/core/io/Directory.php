<?php
namespace core\io
{
    use Exception;
    use FilesystemIterator;
    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;
    use task\googledrive\SubversionFilterIterator;

    class Directory
    {
        protected $path;

        public function __construct($path, $create = false)
        {
            if ($create and !file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if (!file_exists($path)) {
                throw new Exception("Directory '{$path}' does not exist");
            }
            $this->path = $path;
        }

        public function clear()
        {
            $directoryIterator = new RecursiveDirectoryIterator($this->path, FilesystemIterator::SKIP_DOTS);
            $filterIterator = new SubversionFilterIterator($directoryIterator);
            $iterator = new RecursiveIteratorIterator($filterIterator, RecursiveIteratorIterator::CHILD_FIRST);
            foreach($iterator as $path) {
                /** @var $path \SplFileInfo */
                if ($path->isFile()) {
                    unlink($path->getPathname());
                } else {
                    rmdir($path->getPathname());
                }
            }
        }

        public function getPath()
        {
            return $this->path;
        }

        public function copyTo(Directory $target)
        {
            $directoryIterator = new RecursiveDirectoryIterator($this->path, RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);
            foreach ($iterator as $path) {
                /** @var $iterator \RecursiveDirectoryIterator */
                $fullTargetPath = $target->getPath() . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                /** @var $path \SplFileInfo */
                if ($path->isDir()) {
                    mkdir($fullTargetPath);
                } else {
                    copy($path, $fullTargetPath);
                }
            }
        }
    }
}