<?php
declare(strict_types=1);

namespace PHP_CodeSniffer\SniffReference;

use Roave\BetterReflection\Reflection\ReflectionClass;

class SniffStandard
{
    /** @var string */
    private $standardName;

    public function __construct(ReflectionClass $class)
    {
        if (preg_match(
                '/^PHP_CodeSniffer\\\Standards\\\(\w+)\\\Sniffs\\\(?:\w+)\\\(?:\w+)Sniff$/',
                $class->getName(),
                $matches
            ) !== 1
        ) {
            throw
                new IndeterminableSniffPath(
                    sprintf(
                        'The sniff standard name can not be determined from class name "%s".', $class->getName()
                    )
                );
        }

        $this->standardName = $matches[1];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->standardName;
    }
}
