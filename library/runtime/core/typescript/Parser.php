<?php
namespace core\typescript
{
    use Exception;

    class Parser
    {
        private $basePath;
        private $paths;

        public function __construct($basePath)
        {
            if (!file_exists((string) $basePath)) {
                throw new Exception("The path '{$basePath}' does not exist");
            }
            $this->basePath = realpath($basePath);
            $this->paths = $this->collectPaths($basePath, 'Main', array());
        }

        public function collectDependencies()
        {
            $result = array();
            $baseLength = strlen($this->basePath);
            foreach ($this->paths['js'] as $path) {
                if (substr($path, 0, $baseLength) != $this->basePath) {
                    throw new Exception("The path '{$path}' is outside of the base '{$this->basePath}'");
                }
                $result[] = substr($path, $baseLength + 1);
            }
            return $result;
        }

        private function collectPaths($basePath, $relativePath, $pathsVisited)
        {
            $fullPath = "{$basePath}/{$relativePath}.ts";
            $pathsVisited[] = $fullPath;
            $dirName = dirname($fullPath);

            $content = preg_replace('#\s+#', '', file_get_contents($fullPath));
            preg_match_all('#///<referencepath="([^\"]+)"/>#', $content, $matches);

            $typeScriptFiles = array();
            $javaScriptFiles = array();

            foreach ($matches[1] as $match) {

                // for all typescript files process them
                $filePath = realpath("{$dirName}/{$match}.ts");
                if ($filePath) {
                    $typeScriptFiles[] = $filePath;
                    if (!in_array($filePath, $pathsVisited)) {
                        $paths = $this->collectPaths($dirName, $match, $pathsVisited);
                        foreach ($paths['ts'] as $path) {
                            if (!in_array($path, $typeScriptFiles)) {
                                $typeScriptFiles[] = $path;
                            }
                        }
                        foreach ($paths['js'] as $path) {
                            if (!in_array($path, $javaScriptFiles)) {
                                $javaScriptFiles[] = $path;
                            }
                        }
                    }
                }

                // add the corresponding javascript file
                $tsDependency = realpath("{$dirName}/{$match}.ts");
                if ($tsDependency and !in_array($tsDependency, $typeScriptFiles)) {
                    $typeScriptFiles[] = $tsDependency;
                }
                $cleanName = (substr($match, -2) == '.d') ? substr($match, 0, -2) : $match;
                $jsDependency = realpath("{$dirName}/{$cleanName}.js");
                if ($jsDependency and !in_array($jsDependency, $javaScriptFiles)) {
                    $javaScriptFiles[] = $jsDependency;
                }
            }

            // add itself
            $tsDependency = realpath("{$basePath}/{$relativePath}.ts");
            if ($tsDependency and !in_array($tsDependency, $typeScriptFiles)) {
                $typeScriptFiles[] = $tsDependency;
            }
            $cleanName = (substr($relativePath, -2) == '.d') ? substr($relativePath, 0, -2) : $relativePath;
            $jsDependency = realpath("{$basePath}/{$cleanName}.js");
            if ($jsDependency and !in_array($jsDependency, $javaScriptFiles)) {
                $javaScriptFiles[] = $jsDependency;
            }

            return array(
                'ts' => $typeScriptFiles,
                'js' => $javaScriptFiles,
            );
        }
    }
}