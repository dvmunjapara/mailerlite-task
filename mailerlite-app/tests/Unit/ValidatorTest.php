<?php

use App\Request\Validator;

it('can validate email', function () {

    $validator = new Validator();

    $validator->validateEmail('');
    expect($validator->hasErrors())->toBeTrue()
        ->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateEmail('john');
    expect($validator->hasErrors())->toBeTrue()
        ->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateEmail('john@doe.com');
    expect($validator->hasErrors())->toBeFalse();
});

it('can validate name', function () {

    $validator = new Validator();
    $validator->validateName('');
    expect($validator->hasErrors())->toBeTrue()
        ->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateName('john12');
    expect($validator->hasErrors())->toBeTrue()
        ->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateName('John');
    expect($validator->hasErrors())->toBeFalse();
});


it('can validate last name', function () {

    $validator = new Validator();
    $validator->validateLastName('');
    expect($validator->hasErrors())->toBeTrue()->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateLastName('doe1');
    expect($validator->hasErrors())->toBeTrue()->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateLastName('Doe');
    expect($validator->hasErrors())->toBeFalse();
});

it('can validate status', function () {

    $validator = new Validator();
    $validator->validateStatus(null);
    expect($validator->hasErrors())->toBeTrue()
        ->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateStatus(2);
    expect($validator->hasErrors())->toBeTrue()
        ->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateStatus(0);
    expect($validator->hasErrors())->toBeFalse()
        ->and($validator->getErrors())->toBeArray();

    $validator = new Validator();
    $validator->validateStatus(1);
    expect($validator->hasErrors())->toBeFalse();
});
