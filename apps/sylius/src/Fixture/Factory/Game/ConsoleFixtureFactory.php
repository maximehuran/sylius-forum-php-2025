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

namespace App\Fixture\Factory\Game;

use App\Entity\Game\ConsoleInterface;
use App\Entity\Game\ConstructorInterface;
use App\Repository\Game\ConstructorRepositoryInterface;
use Faker\Factory;
use Faker\Generator;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsoleFixtureFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    private Generator $faker;

    private readonly OptionsResolver $optionsResolver;

    public function __construct(
        private readonly FactoryInterface $consoleFactory,
        private readonly ConstructorRepositoryInterface $constructorRepository,
    ) {
        $this->optionsResolver = new OptionsResolver();
        $this->faker = Factory::create();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): ConsoleInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ConsoleInterface $console */
        $console = $this->consoleFactory->createNew();
        $console->setName($options['name']);
        $console->setLogo($options['logo']);
        $console->setConstructor($options['constructor']);

        return $console;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('name')
            ->setDefault('name', fn (Options $options): string => $this->faker->unique()->company)
            ->setAllowedTypes('name', 'string')
            ->setRequired('logo')
            ->setDefault('logo', null)
            ->setAllowedTypes('logo', ['null', 'string'])
            ->setDefault('constructor', LazyOption::randomOne($this->constructorRepository))
            ->setAllowedTypes('constructor', ['null', 'string', ConstructorInterface::class])
            ->setNormalizer('constructor', LazyOption::getOneBy($this->constructorRepository, 'name'))
        ;
    }
}
