<?php
declare(strict_types=1);

namespace PHP_CodeSniffer\SniffReference;

use Roave\BetterReflection\Reflection\ReflectionClass;

class SniffDescriptionText
{
    /** @var string */
    private $descriptionText;

    public function __construct(ReflectionClass $class)
    {
        $source = $class->getLocatedSource()->getSource();

        $docCommentTokens =
            \array_filter(
                \token_get_all($source),
                function($item) {
                    return $item[0] === T_DOC_COMMENT;
                }
            );
        $docBlock = reset($docCommentTokens);

        if (!preg_match('~^/\*\*\n(.*)$~m', $docBlock[1], $matches)) {
            throw
                new UnusableDocCommentException('No valuable doc comment in class ' . $class->getName());
        }

        $this->descriptionText = ltrim($matches[1], ' *');
    }

    public function __toString(): string
    {
        return $this->descriptionText;
    }
}
