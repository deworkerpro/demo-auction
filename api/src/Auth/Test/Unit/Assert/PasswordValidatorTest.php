<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Assert;

use App\Auth\Assert\Password\Password;
use App\Auth\Assert\Password\PasswordValidator;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @internal
 * @extends ConstraintValidatorTestCase<PasswordValidator>
 */
#[CoversClass(PasswordValidator::class)]
final class PasswordValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @param string[] $errors
     */
    #[DataProvider('values')]
    public function testValidate(string $value, array $errors): void
    {
        $this->validator->validate($value, new Password());

        if (\count($errors) > 0) {
            $violation = $this->buildViolation(array_shift($errors));
            foreach ($errors as $error) {
                $violation = $violation->buildNextViolation($error);
            }
            $violation->assertRaised();
        } else {
            $this->assertNoViolation();
        }
    }

    public static function values(): iterable
    {
        return [
            ['password', [
                'Password should contain at least one capital letter.',
                'Password should contain at least one digit.',
                'Password should contain at least one special character.',
            ]],
            ['N9W_PAS#SWORD', [
                'Password should contain at least one small letter.',
            ]],
            ['new-password', [
                'Password should contain at least one capital letter.',
                'Password should contain at least one digit.',
            ]],
            ['new-pasSword', [
                'Password should contain at least one digit.',
            ]],
            ['new#pasSword', [
                'Password should contain at least one digit.',
            ]],
            ['n9w-password', [
                'Password should contain at least one capital letter.',
            ]],
            ['n9w pasSword', [
                'Password should not contain any white space.',
            ]],
            ['n9w-pasSword', []],
            ['n9w#pasSword', []],
            ['n9w#pasS_word', []],
            ['n9wpasS_word', []],
        ];
    }

    #[Override]
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new PasswordValidator();
    }
}
