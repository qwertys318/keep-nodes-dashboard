<?php namespace App\Form\Constraint;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class EthAddress extends Constraint
{
    public $message = 'The value isn\'t eth address.';
}
