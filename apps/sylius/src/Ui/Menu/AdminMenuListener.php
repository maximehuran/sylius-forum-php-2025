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

namespace App\Ui\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
    event: MainMenuBuilder::EVENT_NAME,
)]
final class AdminMenuListener
{
    public function __invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $this->removeOfficialSupportSection($menu);

        $gameSection = $menu
            ->addChild('app_games')
            ->setLabel('app.menu.games.section')
            ->setLabelAttribute('icon', 'tabler:device-gamepad-2')
            ->setExtra('always_open', true)
        ;

        $gameSection
            ->addChild('app_constructor', ['route' => 'app_admin_constructor_index'])
            ->setLabel('app.menu.games.constructor')
        ;

        $gameSection
            ->addChild('app_console', ['route' => 'app_admin_console_index'])
            ->setLabel('app.menu.games.console')
        ;

        $gameSection
            ->addChild('app_game', ['route' => 'app_admin_game_index'])
            ->setLabel('app.menu.games.game')
        ;

        $this->reorderChildren($menu);
    }

    /**
     * Put our game section at the top of the menu after dashboard.
     */
    private function reorderChildren(ItemInterface $menu): void
    {
        $menu->reorderChildren(array_merge(['dashboard', 'app_games'], array_diff(array_keys($menu->getChildren()), ['dashboard', 'app_games'])));
    }

    /**
     * Remove the official support section.
     */
    private function removeOfficialSupportSection(ItemInterface $menu): void
    {
        if ($menu->getChild('official_support')) {
            $menu->removeChild('official_support');
        }
    }
}
