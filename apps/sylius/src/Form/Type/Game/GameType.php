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

namespace App\Form\Type\Game;

use App\Entity\Game\Console;
use Doctrine\ORM\EntityRepository;
use MonsieurBiz\SyliusMediaManagerPlugin\Form\Type\ImageType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GameType extends AbstractResourceType
{
    public function __construct(
        #[Autowire('%app.model.game.class%')]
        string $dataClass,
        #[Autowire(['app'])]
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.ui.name',
                'required' => true,
            ])
            ->add('cover', ImageType::class, [
                'label' => 'app.ui.cover',
                'required' => false,
            ])
            ->add('consoles', EntityType::class, [
                'class' => Console::class,
                'label' => 'app.ui.consoles',
                'multiple' => true,
                'required' => true,
                'choice_label' => 'name',
                'autocomplete' => true,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('o')
                        ->orderBy('o.name', 'ASC')
                    ;
                },
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => GameTranslationType::class,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_game';
    }
}
