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

use App\Entity\Game\GameInterface;
use App\Entity\Game\GameTranslationInterface;
use App\Repository\Game\ConsoleRepositoryInterface;
use Faker\Factory;
use Faker\Generator;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameFixtureFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    private Generator $faker;

    private readonly OptionsResolver $optionsResolver;

    public function __construct(
        private readonly FactoryInterface $gameFactory,
        private readonly FactoryInterface $gameTranslationFactory,
        private readonly ConsoleRepositoryInterface $consoleRepository,
        private readonly RepositoryInterface $localeRepository,
    ) {
        $this->optionsResolver = new OptionsResolver();
        $this->faker = Factory::create();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): GameInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var GameInterface $game */
        $game = $this->gameFactory->createNew();

        // Entity field
        $game->setCover($options['cover']);

        // Translations
        $this->createTranslations($game, $options);

        // Console relation
        $consoles = $options['consoles'];
        foreach ($consoles as $console) {
            $game->addConsole($console);
        }

        return $game;
    }

    private function createTranslations(GameInterface $game, array $options): void
    {
        foreach ($options['translations'] as $localeCode => $translation) {
            /** @var GameTranslationInterface $gameTranslation */
            $gameTranslation = $this->gameTranslationFactory->createNew();
            $gameTranslation->setLocale($localeCode);
            $gameTranslation->setName($translation['name']);

            $game->addTranslation($gameTranslation);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('cover')
            ->setDefault('cover', null)
            ->setAllowedTypes('cover', ['null', 'string'])
            ->setDefault('translations', function (OptionsResolver $translationResolver): void {
                $translationResolver->setDefaults($this->configureDefaultTranslations());
            })
            ->setDefault('consoles', LazyOption::randomOnes($this->consoleRepository, 2))
            ->setAllowedTypes('consoles', ['array'])
            ->setNormalizer('consoles', LazyOption::findBy($this->consoleRepository, 'name'))
        ;
    }

    private function configureDefaultTranslations(): array
    {
        $translations = [];
        $locales = $this->localeRepository->findAll();
        /** @var LocaleInterface $locale */
        foreach ($locales as $locale) {
            $name = ucfirst($this->faker->sentence(3, true));
            $translations[$locale->getCode()] = [
                'name' => $name,
            ];
        }

        return $translations;
    }
}
