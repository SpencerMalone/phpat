<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\AllAttributesRule;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttribute;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<AllAttributesRule>
 * @internal
 * @coversNothing
 */
class ShowRuleNameClassAttributeTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldNotDependSimpleAndSpecial';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s: %s should not depend on %s', self::RULE_NAME, FixtureClass::class, SimpleAttribute::class), 29],
            [sprintf('%s: %s should not depend on %s', self::RULE_NAME, FixtureClass::class, SimpleAttribute::class), 33],
            [sprintf('%s: %s should not depend on %s', self::RULE_NAME, FixtureClass::class, SimpleAttribute::class), 34],
            [sprintf('%s: %s should not depend on %s', self::RULE_NAME, FixtureClass::class, SimpleAttribute::class), 94],
            [sprintf('%s: %s should not depend on %s', self::RULE_NAME, FixtureClass::class, SimpleAttribute::class), 95],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotDepend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleAttribute::class, false)]
        );

        return new AllAttributesRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
