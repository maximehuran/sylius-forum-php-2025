<?php

/*
 * This file is part of a proprietary project.
 *
 * (c) Maxime Huran <m.huran@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Form\Extension\Game;

use App\Entity\Game\Console;
use App\Form\Type\Game\ConstructorType;
use App\Repository\Game\ConsoleRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

/**
 * It's a form extension for the example
 * It should be on the form type, but for the conference, I want to separate it.
 */
class ConstructorExtensionType extends AbstractTypeExtension
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('consoles', LiveCollectionType::class, [
                'label' => 'app.ui.consoles',
                'required' => true,
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => Console::class,
                    'choice_label' => 'name',
                    'autocomplete' => true,
                    'query_builder' => function (ConsoleRepositoryInterface $repository) {
                        /** @phpstan-ignore-next-line  */
                        return $repository->createQueryBuilder('o')
                            ->orderBy('o.name', 'ASC')
                        ;
                    },
                ],
                'attr' => [
                    'class' => 'mb-3 bg-light p-3 rounded border border-secondary-light',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'button_add_options' => [
                    'label' => 'sylius.ui.add',
                ],
                'button_delete_options' => [
                    'label' => 'sylius.ui.delete',
                    'attr' => [
                        'class' => 'btn-outline-danger',
                    ],
                ],
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ConstructorType::class];
    }
}
