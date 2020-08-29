<?php namespace App\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Web3\Utils;

class EthAddressValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EthAddress) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\EthAddress');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!Utils::isAddress($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
