<?php
namespace Tests\Twig;

use App\Framework\Twig\TextExtension;
use PHPUnit\Framework\TestCase;

class TextExtensionTest extends TestCase
{
    /**
     * @var TextExtension
     */
    private $textExtension;

    public function setUp()
    {
        $this->textExtension = new TextExtension();
    }

    public function testExcerptWithShortText()
    {
          $text = "Salut";
         $this->assertEquals('Salut...', $this->textExtension->excerpt($text, 10));
    }

    public function testExcerptWithLongText()
    {
        $text = "Salut ls gens";
        $this->assertEquals('Salut...', $this->textExtension->excerpt($text, 7));
        $this->assertEquals('Salut les...', $this->textExtension->excerpt($text, 12));
    }
}
