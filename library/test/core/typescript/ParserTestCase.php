<?php
namespace core\typescript
{
    use UnitTestCase;

    class ParserTestCase extends UnitTestCase
    {
        public function testParser()
        {
            $basePath = __DIR__ . '/../../../../public/js';
            $parser = new Parser($basePath);

            print_r($parser->parseSignatures());
        }
    }
}