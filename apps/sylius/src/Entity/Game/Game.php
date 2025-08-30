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

use App\Form\Type\Game\GameType;
use App\Grid\Game\GameGrid;
use App\Repository\Game\GameRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Resource\Annotation\SyliusCrudRoutes;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Model\TranslationInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Assert\Assert as WebmozartAssert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ORM\Table(name: 'app_game')]
#[AsResource]
#[SyliusCrudRoutes(
    alias: 'app.game',
    except: ['show'],
    form: GameType::class,
    grid: GameGrid::class,
    path: '/%sylius_admin.path_name%/games',
    permission: true,
    redirect: 'update',
    section: 'admin',
    templates: '@SyliusAdmin/shared/crud',
)]
class Game implements GameInterface
{
    use TimestampableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;

        getTranslation as private doGetTranslation;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cover = null;

    #[Assert\Count(min: 1)]
    #[ORM\JoinTable(name: 'app_game_console')]
    #[ORM\ManyToMany(targetEntity: Console::class)]
    private Collection $consoles;

    /**
     * @var ?DateTimeInterface
     */
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    protected $createdAt;

    /**
     * @var ?DateTimeInterface
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime')]
    #[Gedmo\Timestampable(on: 'update')]
    protected $updatedAt;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->consoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): void
    {
        $this->cover = $cover;
    }

    public function getConsoles(): Collection
    {
        return $this->consoles;
    }

    public function hasConsole(ConsoleInterface $console): bool
    {
        return $this->consoles->contains($console);
    }

    public function addConsole(ConsoleInterface $console): void
    {
        if (!$this->hasConsole($console)) {
            $this->consoles->add($console);
        }
    }

    public function removeConsole(ConsoleInterface $console): void
    {
        $this->consoles->removeElement($console);
    }

    protected function createTranslation(): GameTranslation
    {
        return new GameTranslation();
    }

    /**
     * @return GameTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface
    {
        $translation = $this->doGetTranslation($locale);
        WebmozartAssert::isInstanceOf($translation, GameTranslationInterface::class);

        return $translation;
    }
}
