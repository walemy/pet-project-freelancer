<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

//uses(\Tests\FeatureTestCase::class)->in(
//    '../src/User/Tests/Feature',
//);

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

use Freelance\User\Domain\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeInvalid', function ($errors) {
    try {
        $this->value->__invoke();
        test()->fail('No validation exception was thrown!');
    }
    catch (ValidationException $exception) {
        foreach ($errors as $key => $error) {
            expect(json_encode($exception->errors()[$key], JSON_THROW_ON_ERROR))->toContain($error);
        }
    }
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function login(User $user = null, array $abilities = ['*'])
{
    $user = $user ?: User::factory()->email('test@test.test')->create();
    Sanctum::actingAs(
        $user,
        $abilities
    );
}
