#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
 * Builds the reference documentation of available sniffs.
 *
 * @license https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

use PHP_CodeSniffer\SniffReference\IndeterminableSniffPath;
use PHP_CodeSniffer\SniffReference\SniffDescription;
use PHP_CodeSniffer\SniffReference\SniffDescriptionText;
use PHP_CodeSniffer\SniffReference\UnusableDocCommentException;
use PHP_CodeSniffer\Sniffs\Sniff;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

require __DIR__ . '/../vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('PHP_CodeSniffer\SniffReference\\', __DIR__.'/SniffReference');
$loader->register();

$astLocator = (new BetterReflection())->astLocator();
$directoriesSourceLocator =
    new AggregateSourceLocator([
        //new PhpInternalSourceLocator($astLocator),
        //new DirectoriesSourceLocator([__DIR__.'/../tests/Standards'], $astLocator),
        new SingleFileSourceLocator(__DIR__.'/../src/Sniffs/Sniff.php', $astLocator),
        new DirectoriesSourceLocator([__DIR__.'/../src/Standards'], $astLocator)
    ]);

$reflector = new ClassReflector($directoriesSourceLocator);
$classes = $reflector->getAllClasses();

$sniffClasses =
    // disregard all classes that are not of interface \PHP_CodeSniffer\Sniffs\Sniff
    array_filter(
        $classes,
        function (ReflectionClass $class) {
            return
                \in_array(
                    Sniff::class,
                    \array_values(
                        \array_map(
                            function (ReflectionClass $interface) : string {
                                return $interface->getName();
                            },
                            $class->getImmediateInterfaces()
                        )
                    ),
                    true
                );
        }
    );

foreach ($sniffClasses as $class) {

    try {
        echo (new SniffDescription($class))->printMarkdown();
        echo "\n\n\n";
    } catch (
        UnusableDocCommentException |
        IndeterminableSniffPath $e
    ) {
        echo $e->getMessage() . "\n";
        continue;
    }
}
