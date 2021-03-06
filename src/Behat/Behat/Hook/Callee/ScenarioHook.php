<?php

namespace Behat\Behat\Hook\Callee;

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Behat\Behat\Event\EventInterface;
use Behat\Behat\Event\OutlineEvent;
use Behat\Behat\Event\ScenarioEvent;
use Behat\Gherkin\Filter\NameFilter;
use Behat\Gherkin\Filter\TagFilter;
use RuntimeException;

/**
 * Base ScenarioHook hook class.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
abstract class ScenarioHook extends FilterableHook
{
    /**
     * Checks if provided event matches hook filter.
     *
     * @param EventInterface $event
     *
     * @return Boolean
     *
     * @throws RuntimeException
     */
    public function filterMatches(EventInterface $event)
    {
        if (null === ($filterString = $this->getFilterString())) {
            return true;
        }

        if ($event instanceof ScenarioEvent) {
            $scenario = $event->getScenario();
        } elseif ($event instanceof OutlineEvent) {
            $scenario = $event->getOutline();
        } else {
            throw new RuntimeException(sprintf(
                'Unsupported type of scenario event: %s', get_class($event)
            ));
        }

        if (false !== strpos($filterString, '@')) {
            $filter = new TagFilter($filterString);

            if ($filter->isScenarioMatch($scenario)) {
                return true;
            }
        } elseif (!empty($filterString)) {
            $filter = new NameFilter($filterString);

            if ($filter->isScenarioMatch($scenario)) {
                return true;
            }
        }

        return false;
    }
}
