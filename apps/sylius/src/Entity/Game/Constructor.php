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

use App\Form\Type\Game\ConstructorType;
use App\Grid\Game\ConstructorGrid;
use App\Repository\Game\ConstructorRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Resource\Annotation\SyliusCrudRoutes;
use Sylius\Resource\Metadata\AsResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConstructorRepository::class)]
#[ORM\Table(name: 'app_constructor')]
#[AsResource]
#[SyliusCrudRoutes(
    alias: 'app.constructor',
    except: ['show'],
    form: ConstructorType::class,
    grid: ConstructorGrid::class,
    path: '/%sylius_admin.path_name%/constructors',
    permission: true,
    redirect: 'update',
    section: 'admin',
    templates: '@SyliusAdmin/shared/crud',
)]
class Constructor implements ConstructorInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

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

    #[ORM\OneToMany(mappedBy: 'constructor', targetEntity: Console::class)]
    private Collection $consoles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    public function getConsoles(): Collection
    {
        return $this->consoles;
    }

    public function addConsole(Console $console): void
    {
        if (!$this->consoles->contains($console)) {
            $this->consoles[] = $console;
            $console->setConstructor($this);
        }
    }

    public function removeConsole(Console $console): void
    {
        if ($this->consoles->removeElement($console)) {
            if ($console->getConstructor() === $this) {
                $console->setConstructor(null);
            }
        }
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
