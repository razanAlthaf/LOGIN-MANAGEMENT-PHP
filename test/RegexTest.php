<?php

namespace Razan\belajar\php\mvc\App;

use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase
{
    public function testRegex()
    {
        $path = "/products/123/categoeries/abc";

        $pattern = "#^/products/([0-9]*)/categories/([a-zA-Z]*)$#";

        $resdult = preg_match($pattern, $path, $variables);

        self::assertEquals(1, true);

        array_shift($variables);
    }
}