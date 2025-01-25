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

use App\Form\Type\Game\ConsoleType;
use App\Grid\Game\ConsoleGrid;
use App\Repository\Game\ConsoleRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Resource\Annotation\SyliusCrudRoutes;
use Sylius\Resource\Metadata\AsResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsoleRepository::class)]
#[ORM\Table(name: 'app_console')]
#[AsResource]
#[SyliusCrudRoutes(
    alias: 'app.console',
    except: ['show'],
    form: ConsoleType::class,
    grid: ConsoleGrid::class,
    path: '/%sylius_admin.path_name%/consoles',
    permission: true,
    redirect: 'update',
    section: 'admin',
    templates: '@SyliusAdmin/shared/crud',
)]
class Console implements ConsoleInterface
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

    #[ORM\ManyToOne(targetEntity: Constructor::class, inversedBy: 'consoles')]
    #[ORM\JoinColumn(name: 'constructor_id', referencedColumnName: 'id', nullable: true)]
    private ?ConstructorInterface $constructor = null;

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

    public function getConstructor(): ?ConstructorInterface
    {
        return $this->constructor;
    }

    public function setConstructor(?ConstructorInterface $constructor): void
    {
        $this->constructor = $constructor;
    }
}
