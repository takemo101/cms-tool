<?php

use CmsTool\Support\Validation\FormRequestObject;
use CmsTool\Support\Validation\Mapper\CamelCaseMapper;
use Takemo101\Chubby\Contract\Arrayable;

describe(
    'FormRequestObject',
    function () {
        it(
            'should populate properties correctly',
            function () {
                $expectedArray = [
                    'person_name' => 'John Doe',
                    'age' => 25,
                    'address' => [
                        'street' => '123 Main St',
                        'city' => 'New York',
                        'country' => 'USA',
                    ],
                ];

                $request = new #[CamelCaseMapper] class($expectedArray) extends FormRequestObject
                {
                    public string $personName;
                    public int $age;
                    public AddressFormRequest $address;
                };

                expect($request->personName)->toBe($expectedArray['person_name']);
                expect($request->age)->toBe($expectedArray['age']);
                expect($request->address->toArray())->toBe($expectedArray['address']);
            }
        );

        it(
            'should convert the object to array representation',
            function () {
                $request = new class() extends FormRequestObject
                {
                    public string $name = 'John Doe';
                    public int $age = 25;
                    public array $address = [
                        'street' => '123 Main St',
                        'city' => 'New York',
                        'country' => 'USA',
                    ];
                };

                $expectedArray = [
                    'name' => 'John Doe',
                    'age' => 25,
                    'address' => [
                        'street' => '123 Main St',
                        'city' => 'New York',
                        'country' => 'USA',
                    ],
                ];

                expect($request->toArray())->toBe($expectedArray);
            }
        );

        it(
            'should exclude properties from array representation',
            function () {
                $request = new class() extends FormRequestObject
                {
                    public string $name = 'John Doe';
                    public int $age = 25;
                    public array $address = [
                        'street' => '123 Main St',
                        'city' => 'New York',
                        'country' => 'USA',
                    ];

                    public const ExcludeArrayProperties = ['age'];
                };

                $expectedArray = [
                    'name' => 'John Doe',
                    'address' => [
                        'street' => '123 Main St',
                        'city' => 'New York',
                        'country' => 'USA',
                    ],
                ];

                expect($request->toArray())->toBe($expectedArray);
            }
        );

        it(
            'should populate properties using the populate method',
            function () {
                $expectedArray = [
                    'name' => 'John Doe',
                    'age' => 25,
                    'address' => [
                        'street' => '123 Main St',
                        'city' => 'New York',
                        'country' => 'USA',
                    ],
                ];

                $request = new class() extends FormRequestObject
                {
                    public string $name;
                    public int $age;
                    public AddressFormRequest $address;
                };

                $request->populate($expectedArray);

                expect($request->name)->toBe($expectedArray['name']);
                expect($request->age)->toBe($expectedArray['age']);
                expect($request->address->toArray())->toBe($expectedArray['address']);
            }
        );
    }
)->group('form-request-object', 'validation');

class AddressFormRequest extends FormRequestObject
{
    public string $street;
    public string $city;
    public string $country;
};
