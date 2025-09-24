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

namespace App\Entity\Game;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TimestampableInterface;

interface GameInterface extends ResourceInterface, TranslatableInterface, TimestampableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getCover(): ?string;

    public function setCover(?string $cover): void;

    public function getConsoles(): Collection;

    public function hasConsole(ConsoleInterface $console): bool;

    public function addConsole(ConsoleInterface $console): void;

    public function removeConsole(ConsoleInterface $console): void;
}
