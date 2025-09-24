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

namespace App\Fixture\Game;

use App\Fixture\Factory\Game\ConsoleFixtureFactory;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ConsoleFixture extends AbstractResourceFixture
{
    public function __construct(
        EntityManagerInterface $consoleManager,
        ConsoleFixtureFactory $consoleFixtureFactory,
    ) {
        parent::__construct($consoleManager, $consoleFixtureFactory);
    }

    public function getName(): string
    {
        return 'app_console';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        /** @phpstan-ignore-next-line */
        $resourceNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('logo')->defaultNull()->end()
                ->scalarNode('constructor')->defaultNull()->end()
            ->end()
        ;
    }
}
