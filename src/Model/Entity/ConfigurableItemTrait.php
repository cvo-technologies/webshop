<?php

namespace Webshop\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;
use Webshop\PriceContainer;

trait ConfigurableItemTrait
{

    /**
     * @return \Cake\Collection\Collection
     */
    public function configuration()
    {
        $configuration = [];
        /* @var ItemConfigurationValue $configurationValue */
        foreach ($this->configuration_values as $configurationValue) {
            $alias = $configurationValue->configuration_option->alias;

            $configuration[$alias] = $configurationValue->value();
        }

        return collection($configuration);
    }

    /**
     * @return array
     */
    public function configurationValueIds()
    {
        $ids = [];

        /* @var ItemConfigurationValue $configurationValue */
        foreach ($this->configuration_values as $configurationValue) {
            $alias = $configurationValue->configuration_option->alias;

            $ids[$alias] = $configurationValue->id;
        }

        return $ids;
    }

    /**
     * @return PriceContainer
     */
    public function configurationPrice()
    {
        $priceContainer = PriceContainer::construct();

        /* @var ItemConfigurationValue $configurationValue */
        foreach ($this->configuration_values as $configurationValue) {
            $priceContainer->add($configurationValue->price());
        }

        return $priceContainer;
    }

    /**
     * Applies configuration to a empty
     *
     * @param Entity $entity Entity to apply the configuration to
     *
     * @return void
     */
    public function applyConfiguration(Entity $entity)
    {
        /* @var ConfigurableItemTrait $entity */

        $this->configuration_values = [];

        /* @var ItemConfigurationValue $sourceConfigurationValue */
        foreach ($entity->configuration_values as $sourceConfigurationValue) {
            $configurationValue = clone $sourceConfigurationValue;

            $configurationValue->isNew(true);

            $configurationValue->unsetProperty('id');
            $configurationValue->model = get_class($this);
            $configurationValue->unsetProperty('foreign_key');

            $this->configuration_values[] = $configurationValue;
        }

//        $entity->
    }
}
