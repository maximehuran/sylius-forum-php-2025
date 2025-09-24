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

use App\Fixture\Factory\Game\GameFixtureFactory;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class GameFixture extends AbstractResourceFixture
{
    public function __construct(
        EntityManagerInterface $gameManager,
        GameFixtureFactory $gameFixtureFactory,
    ) {
        parent::__construct($gameManager, $gameFixtureFactory);
    }

    public function getName(): string
    {
        return 'app_game';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        /** @phpstan-ignore-next-line */
        $resourceNode
            ->children()
                ->scalarNode('cover')->defaultNull()->end()
                ->arrayNode('consoles')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('translations')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->end()
        ;
    }
}
