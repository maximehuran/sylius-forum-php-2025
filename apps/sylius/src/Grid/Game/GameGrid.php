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

namespace App\Grid\Game;

use App\Entity\Game\Console;
use App\Entity\Game\Game;
use App\Repository\Game\ConsoleRepositoryInterface;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\BulkActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\ItemActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\MainActionGroup;
use Sylius\Bundle\GridBundle\Builder\Field\DateTimeField;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\SelectFilter;
use Sylius\Bundle\GridBundle\Builder\Filter\StringFilter;
use Sylius\Bundle\GridBundle\Builder\GridBuilderInterface;
use Sylius\Bundle\GridBundle\Grid\AbstractGrid;
use Sylius\Bundle\GridBundle\Grid\ResourceAwareGridInterface;

class GameGrid extends AbstractGrid implements ResourceAwareGridInterface
{
    public function __construct(private ConsoleRepositoryInterface $consoleRepository)
    {
    }

    public static function getName(): string
    {
        return 'app_game';
    }

    public function buildGrid(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createListQueryBuilder', ["expr:service('sylius.context.locale').getLocaleCode()"]) // To get with translations join
            ->addField(
                StringField::create('name')
                    ->setLabel('app.ui.name')
                    ->setSortable(true, 'translation.name') // Sorting by translated field
            )
            ->addField(
                TwigField::create('consoles', 'admin/game/grid/field/consoles.html.twig')
                    ->setLabel('app.ui.consoles')
                    ->setSortable(false)
            )
            ->addField(
                DateTimeField::create('createdAt')
                    ->setLabel('sylius.ui.created_at')
                    ->setSortable(true)
            )
            ->addField(
                DateTimeField::create('updatedAt')
                    ->setLabel('sylius.ui.updated_at')
                    ->setSortable(true)
            )
            ->addActionGroup(
                MainActionGroup::create(
                    CreateAction::create(),
                )
            )
            ->addActionGroup(
                ItemActionGroup::create(
                    UpdateAction::create(),
                    DeleteAction::create()
                )
            )
            ->addActionGroup(
                BulkActionGroup::create(
                    DeleteAction::create()
                )
            )
            ->addFilter(
                StringFilter::create('name')
                    ->setLabel('app.ui.name')
                    ->setOptions([
                        'fields' => ['translation.name'], // Search on translation field
                    ])
            )
            ->addFilter(
                SelectFilter::create('consoles', $this->getConsoleChoices(), true, 'consoles.id')
                    ->setLabel('app.ui.consoles')
            )
        ;
    }

    private function getConsoleChoices(): array
    {
        $choices = [];
        /** @var Console $console */
        foreach ($this->consoleRepository->findAll() as $console) {
            $choices[$console->getName()] = $console->getId();
        }

        ksort($choices);

        return $choices;
    }

    public function getResourceClass(): string
    {
        return Game::class;
    }
}
