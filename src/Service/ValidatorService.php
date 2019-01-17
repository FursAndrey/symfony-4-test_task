<?php

namespace App\Service;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ValidatorService
{
    public function isValid($type, $value)
    {
        $condition = array(
            new NotBlank(),
            new NotNull(),
        );
        switch ($type) {
            case 'name':
                $condition[] = new Length(array(
                    'min' => 3,
                    'max' => 15,
                ));
                $condition[] = new Regex(array('pattern' => '/^[a-z0-9]+$/i'));
                break;
            case 'pass':
                $condition[] = new Length(array(
                    'min' => 3,
                    'max' => 15,
                ));
                $condition[] = new Regex(array('pattern' => '/^[a-z0-9]+$/i'));
                break;
            case 'email':
                $condition[] = new Length(array('max' => 180,));
                $condition[] = new Email(array(
                    'mode' => 'html5',
                    'message' => 'The email "{{ value }}" is not a valid email.',
                ));
                $condition[] = new Regex(
                    array('pattern' => '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i')
                );
                break;
            case 'date':
                $condition[] = new Date();
                $condition[] = new GreaterThan("1900-01-01");
                break;
            case 'int':
                $condition[] = new NotEqualTo(array('value' => 0));
                $condition[] = new Regex(array('pattern' => '/^[a-z0-9]+$/i'));
        }
        $validator = Validation::createValidator();
        $violations = $validator->validate($value, $condition);

        $error = [];
        if (0 !== count($violations)) {
            for ($i = 0; $i < count($violations); $i++) {
                $error[] = $violations[$i]->getMessage();
            }
        }
        return $error;
    }
}
