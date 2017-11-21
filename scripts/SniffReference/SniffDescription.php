<?php
declare(strict_types=1);

namespace PHP_CodeSniffer\SniffReference;

use Roave\BetterReflection\Reflection\ReflectionClass;

class SniffDescription
{
    /** @var SniffPath */
    private $sniffPath;

    /** @var SniffDescriptionText */
    private $text;

    /** @var string */
    private $sniffClassName;

    public function __construct(ReflectionClass $class)
    {
        $this->sniffPath = new SniffPath($class);
        $this->text = new SniffDescriptionText($class);
        $this->sniffClassName = $class->getName();
    }

    public function printMarkdown(): string
    {
        return
            wordwrap(
                sprintf(
                    "#### %s\n\n%s\n\n*declared in `%s`*",
                    $this->sniffPath,
                    $this->text,
                    $this->sniffClassName
                ),
                80
            );
    }
}
