<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

class Filter
{
    /**
     * @var string[]
     */
    private $excludedTags;

    /**
     * @param string[] $excludedTags
     */
    public function __construct(array $excludedTags)
    {
        $this->excludedTags = $excludedTags;
    }

    public function __invoke(Tool $tool): bool
    {
        return $this->excludedTags === \array_diff($this->excludedTags, $tool->tags());
    }
}
