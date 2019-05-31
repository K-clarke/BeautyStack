<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CreatedByPro extends Constraint
{
    public $message = 'Users Can Only Book Styles Made By Pros';
}
