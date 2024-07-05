<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\Setting\AbstractSetting;

/**
 * Factory to create theme schema object from schema data
 */
class ThemeSchemaFactory
{
    /**
     * constructor
     *
     * @param SchemaSettingFactory $factory
     */
    public function __construct(
        private readonly SchemaSettingFactory $factory,
    ) {
        //
    }

    /**
     * Create theme schema object from schema data
     *
     * @param (array{
     *   id?: string,
     *   title?: string,
     *   settings?: (array{
     *     type: string,
     *   }&array<string,mixed>)[]
     * }&array<string,mixed>)[] $data
     * @return ThemeSchema
     * @throws ArrayKeyMissingException
     */
    public function create(array $data): ThemeSchema
    {
        /**
         * @var SchemaSettings[]
         */
        $settings = [];

        foreach ($data as $schema) {
            $settings[] = $this->createSchemaSettings($schema);
        }

        return new ThemeSchema(...$settings);
    }

    /**
     * Create schema settings object from schema data
     *
     * @param array{
     *   id?: string,
     *   title?: string,
     *   settings?: (array{
     *     type: string,
     *   }&array<string,mixed>)[],
     * }&array<string,mixed> $data
     * @return SchemaSettings
     * @throws ArrayKeyMissingException
     */
    private function createSchemaSettings(array $data): SchemaSettings
    {
        $id = new SchemaSettingId($data['id'] ?? ArrayKeyMissingException::throw('id'));
        $title = $data['title'] ?? ArrayKeyMissingException::throw('title');

        /**
         * @var AbstractSetting[]
         */
        $settings = [];

        $schemaSettings = $data['settings'] ?? [];

        foreach ($schemaSettings as $setting) {
            $settings[] = $this->factory->create($setting);
        }

        return new SchemaSettings($id, $title, ...$settings);
    }
}
