<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Exception\ArrayKeyMissingException;

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
     * @param array<string,mixed>[] $data
     * @return ThemeSchema
     * @throws ArrayKeyMissingException
     */
    public function create(array $data): ThemeSchema
    {
        /**
         * @var SchemaSetting[]
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
     *   id: string,
     *   title: string,
     *   settings: array<string,mixed>[]
     * } $data
     * @return SchemaSettings
     * @throws ArrayKeyMissingException
     */
    private function createSchemaSettings(array $data): SchemaSettings
    {
        $id = $data['id'] ?? ArrayKeyMissingException::throw('id');
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
