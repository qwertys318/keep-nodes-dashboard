<?php namespace App\Form\Exception;

use Exception;
use Symfony\Component\Form\FormInterface;

class ValidationException extends Exception
{
    private $form;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
        parent::__construct();
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
