<?php
declare(strict_types=1);

namespace PHP_CodeSniffer\SniffReference;

use Roave\BetterReflection\Reflection\ReflectionClass;

class SniffCategory
{
    /** @var string */
    private $categoryName;

    public function __construct(ReflectionClass $class)
    {
        if (preg_match(
                '/^PHP_CodeSniffer\\\Standards\\\(?:\w+)\\\Sniffs\\\(\w+)\\\(?:\w+)Sniff$/',
                $class->getName(),
                $matches
            ) !== 1
        ) {
            throw
            new IndeterminableSniffPath(
                sprintf(
                    'The sniff category name can not be determined from class name "%s".', $class->getName()
                )
            );
        }

        $this->categoryName = $matches[1];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->categoryName;
    }
}
