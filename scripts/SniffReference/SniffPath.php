<?php
declare(strict_types=1);

namespace PHP_CodeSniffer\SniffReference;

use Roave\BetterReflection\Reflection\ReflectionClass;

class SniffPath
{
    /** @var SniffStandard */
    private $standard;

    /** @var SniffCategory */
    private $category;

    /** @var SniffName */
    private $name;

    public function __construct(ReflectionClass $class)
    {
        $this->standard = new SniffStandard($class);
        $this->category = new SniffCategory($class);
        $this->name = new SniffName($class);
    }

    public function __toString(): string
    {
        return
            sprintf('%s.%s.%s', $this->standard, $this->category, $this->name);
    }
}
