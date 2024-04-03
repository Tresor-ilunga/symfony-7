<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class BanWordValidator
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class BanWordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var BanWord $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $value = strtolower($value);
        foreach ($constraint->banWords as $banWord)
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ banWord }}', $banWord)
                ->addViolation();
        }
    }
}
