<?php

namespace App\Controller\Admin;

use App\Entity\EventUser;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class EventUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventUser::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user_participant')->setLabel('Participant');
        yield AssociationField::new('event')->setLabel('Événement');
    }
}
