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

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TimestampableInterface;

interface ConsoleInterface extends ResourceInterface, TimestampableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getLogo(): ?string;

    public function setLogo(?string $logo): void;

    public function getConstructor(): ?ConstructorInterface;

    public function setConstructor(?ConstructorInterface $constructor): void;
}
