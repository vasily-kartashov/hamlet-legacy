<?php
namespace core\typescript
{
    use Exception;

    class Parser
    {
        public function collectDependencies($dirPath)
        {
            if (!file_exists((string) $dirPath)) {
                throw new Exception("The path '{$dirPath}' does not exist");
            }
            $result = array();
            $paths = $this->collectPaths($dirPath, 'Main',array());
            $base = realPath($dirPath);
            $baseLength = strlen($base);
            foreach ($paths as $path) {
                if (substr($path, 0, $baseLength) != $base) {
                    throw new Exception("The path '{$path}' is outside of the base '{$base}'");
                }
                $result[] = substr($path, $baseLength + 1);
            }
            return $result;
        }

        private function collectPaths($basePath, $relativePath, $pathsVisited) {

            $result = array();
            $fullPath = "{$basePath}/{$relativePath}.ts";
            $pathsVisited[] = $fullPath;
            $dirName = dirname($fullPath);

            $content = preg_replace('#\s+#', '', file_get_contents($fullPath));
            preg_match_all('#///<referencepath="([^\"]+)"/>#', $content, $matches);

            foreach ($matches[1] as $match) {

                // for all typescript files process them
                $filePath = "{$dirName}/{$match}.ts";
                if (file_exists($filePath)) {
                    if (!in_array($filePath, $pathsVisited)) {
                        foreach ($this->collectPaths($dirName, $match,$pathsVisited) as $dependency) {
                            if (!in_array($dependency, $result)) {
                                $result[] = $dependency;
                            }
                        }
                    }
                }
                // add the corresponding javascript file
                $cleanName = (substr($match, -2) == '.d') ? substr($match, 0, -2) : $match;
                $dependency = "{$dirName}/{$cleanName}.js";
                if (file_exists($dependency)) {
                    if (!in_array($dependency, $result)) {
                        $result[] = $dependency;
                    }
                }
            }

            // add itself
            $cleanName = (substr($relativePath, -2) == '.d') ? substr($relativePath, 0, -2) : $relativePath;
            $dependency = "{$basePath}/{$cleanName}.js";
            if (file_exists($dependency)) {
                if (!in_array($dependency, $result)) {
                    $result[] = $dependency;
                }
            }

            return $result;
        }
    }
}