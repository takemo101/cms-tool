<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient\Get;

class MicroCmsGetOneQuery
{
    /** @var MicroCmsContentQuery */
    private MicroCmsContentQuery $query;

    /**
     * constructor
     *
     * @param string|null $draftKey
     * @param string[]|string|null $fields
     * @param integer|null $depth
     */
    public function __construct(
        ?string $draftKey = null,
        array|string|null $fields = null,
        ?int $depth = null,
    ) {
        $this->query = new MicroCmsContentQuery(
            draftKey: $draftKey,
            fields: $fields,
            depth: $depth,
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        return $this->query->toQuery();
    }
}
