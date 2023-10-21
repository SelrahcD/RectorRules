<?php

declare(strict_types=1);

namespace Rector\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MoveConstructorArgumentToMethodArgumentRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataSkip()
     */
    public function testSkip(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return \Iterator<\Symplify\SmartFileSystem\SmartFileInfo>
     */
    public function provideData(): \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    /**
     * @return \Iterator<\Symplify\SmartFileSystem\SmartFileInfo>
     */
    public function provideDataSkip(): \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureSkip');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
