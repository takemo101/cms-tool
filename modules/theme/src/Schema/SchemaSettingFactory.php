<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\Setting\AbstractSetting;
use CmsTool\Theme\Schema\Setting\CheckboxSetting;
use CmsTool\Theme\Schema\Setting\ColorSetting;
use CmsTool\Theme\Schema\Setting\HeaderSetting;
use CmsTool\Theme\Schema\Setting\NumberSetting;
use CmsTool\Theme\Schema\Setting\SelectSetting;
use CmsTool\Theme\Schema\Setting\TextareaSetting;
use CmsTool\Theme\Schema\Setting\TextSetting;
use InvalidArgumentException;
use SplObjectStorage;

/**
 * Factory to create setting objects from schema data
 */
class SchemaSettingFactory
{
    /**
     * @var SplObjectStorage<SchemaSettingType,class-string<AbstractSetting>>
     */
    private readonly SplObjectStorage $map;

    /**
     * constructor
     */
    public function __construct()
    {
        $map = new SplObjectStorage();

        $map[SchemaSettingType::Checkbox] = CheckboxSetting::class;
        $map[SchemaSettingType::Color] = ColorSetting::class;
        $map[SchemaSettingType::Header] = HeaderSetting::class;
        $map[SchemaSettingType::Number] = NumberSetting::class;
        $map[SchemaSettingType::Select] = SelectSetting::class;
        $map[SchemaSettingType::Textarea] = TextareaSetting::class;
        $map[SchemaSettingType::Text] = TextSetting::class;
        // Add more mappings as needed

        $this->map = $map;
    }

    /**
     * Create setting from array data
     *
     * @param array<string,mixed> $data
     * @return AbstractSetting
     * @throws ArrayKeyMissingException
     */
    public function create(
        array $data,
    ): AbstractSetting {

        $type = SchemaSettingType::from(
            $data['type'] ?? ArrayKeyMissingException::throw('type'),
        );

        $class = $this->getSettingClass($type);

        return $class::fromArray($data);
    }

    /**
     * Get the setting class name from the schema setting type
     *
     * @param SchemaSettingType $type
     * @return class-string<AbstractSetting>
     * @throws InvalidArgumentException If type is undefined
     */
    private function getSettingClass(SchemaSettingType $type): string
    {
        if (!$this->map->contains($type)) {
            throw new InvalidArgumentException("Undefined setting type: {$type->value}");
        }

        return $this->map[$type];
    }
}
