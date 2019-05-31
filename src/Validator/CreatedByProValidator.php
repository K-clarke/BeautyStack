<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Style;
use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CreatedByProValidator extends ConstraintValidator
{
    /**
     * @param Style $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Style) {
            return;
        }
        /** @var User $uploadedBy */
        $uploadedBy = $value->getUploadedBy();

        /** @var $constraint \App\Validator\CreatedByPro */
        if (in_array('ROLE_PRO', $uploadedBy->getRoles())) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
