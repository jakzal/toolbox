<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

class Filter
{
    /**
     * @var string[]
     */
    private $excludedTags;

    /**
     * @var string[]
     */
    private $tags;

    /**
     * @param string[] $excludedTags
     * @param string[] $tags
     */
    public function __construct(array $excludedTags, array $tags)
    {
        $this->excludedTags = $excludedTags;
        $this->tags = $tags;
    }

    public function __invoke(Tool $tool): bool
    {
        return $this->excludedTags === \array_diff($this->excludedTags, $tool->tags())
            && (empty($this->tags) || \array_intersect($this->tags, $tool->tags()));
    }
}
