<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_plain_text_decodes_numeric_entities_and_removes_tags(): void
    {
        $html = '<p>&#1052;&#1099; <strong>&#1086;&#1090;&#1082;&#1088;&#1099;&#1083;&#1080;</strong> &amp; запустили сайт.</p>';

        $this->assertSame('Мы открыли & запустили сайт.', HtmlSanitizer::plainText($html));
    }

    public function test_plain_text_normalizes_whitespace(): void
    {
        $html = "<p>Первая\n\t строка</p>   <span>и продолжение</span>";

        $this->assertSame('Первая строка и продолжение', HtmlSanitizer::plainText($html));
    }

    public function test_plain_text_handles_empty_content(): void
    {
        $this->assertSame('', HtmlSanitizer::plainText(null));
    }
}
