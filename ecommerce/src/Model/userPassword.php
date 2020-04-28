<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ChangePassword
{
    public static function loadValidatorData(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint(
            'oldPassword',
            new SecurityAssert\UserPassword([
                'message' => 'Vous devez entrer votre mot de passe actuel',
            ])
        );
    }
}
