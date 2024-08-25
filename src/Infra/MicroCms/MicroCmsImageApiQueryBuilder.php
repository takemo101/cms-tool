<?php

namespace Takemo101\CmsTool\Infra\MicroCms;

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;

class MicroCmsImageApiQueryBuilder
{
    /**
     * @param string|null $url
     * @param array<string,mixed> $params
     * @return string|null
     */
    public function build(?string $url, array $params = []): ?string
    {
        if (empty($url)) {
            return null;
        }

        // Remove query parameters from the URL.
        $exceptedQueryUrl = $this->exceptQuery($url);

        $object = ImmutableArrayObject::of($params);

        $query = array_filter([
            ...$this->buildSizeQuery($object),
            ...$this->buildQualityQuery($object),
            ...$this->buildFormatQuery($object),
            ...$this->buildDprQuery($object),
        ]);

        return $exceptedQueryUrl . (
            empty($query)
            ? ''
            : '?' . http_build_query($query)
        );
    }

    /**
     * Remove query parameters from the URL.
     *
     * @param string $url
     * @return string
     */
    private function exceptQuery(string $url): string
    {
        return strtok($url, '?') ?: $url;
    }

    /**
     * @param ImmutableArrayObject $object
     * @return array<string,mixed>
     */
    private function buildSizeQuery(ImmutableArrayObject $object): array
    {
        return [
            'w' => $object->get(
                'w',
                $object->get('width'),
            ),
            'h' => $object->get(
                'h',
                $object->get('height'),
            ),
            'f' => $object->get(
                'f',
                $object->get('fit'),
            ),
        ];
    }

    /**
     * @param ImmutableArrayObject $object
     * @return array<string,mixed>
     */
    private function buildQualityQuery(ImmutableArrayObject $object): array
    {
        return [
            'q' => $object->get(
                'q',
                $object->get('quality'),
            ),
        ];
    }

    /**
     * @param ImmutableArrayObject $object
     * @return array<string,mixed>
     */
    private function buildFormatQuery(ImmutableArrayObject $object): array
    {
        return [
            'fm' => $object->get(
                'fm',
                $object->get('format'),
            ),
        ];
    }

    private function buildDprQuery(ImmutableArrayObject $object): array
    {
        return [
            'dpr' => $object->get('dpr'),
        ];
    }
}
