<?php
declare(strict_types=1);
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\CartBundle\Domain\Delivery;

use Shopware\Bundle\CartBundle\Domain\LineItem\CalculatedLineItemCollection;
use Shopware\Bundle\CartBundle\Domain\Price\PriceCollection;
use Shopware\Bundle\StoreFrontBundle\Common\Collection;

class DeliveryPositionCollection extends Collection
{
    /**
     * @var DeliveryPosition[]
     */
    protected $elements = [];

    public function add(DeliveryPosition $deliveryPosition): void
    {
        $key = $this->getKey($deliveryPosition);
        $this->elements[$key] = $deliveryPosition;
    }

    public function remove(string $identifier): void
    {
        parent::doRemoveByKey($identifier);
    }

    public function removeElement(DeliveryPosition $deliveryPosition): void
    {
        parent::doRemoveByKey($this->getKey($deliveryPosition));
    }

    public function exists(DeliveryPosition $deliveryPosition): bool
    {
        return parent::has($this->getKey($deliveryPosition));
    }

    public function get(string $identifier): ? DeliveryPosition
    {
        if ($this->has($identifier)) {
            return $this->elements[$identifier];
        }

        return null;
    }

    public function getPrices(): PriceCollection
    {
        return new PriceCollection(
            array_map(
                function (DeliveryPosition $position) {
                    return $position->getPrice();
                },
                $this->elements
            )
        );
    }

    public function getLineItems(): CalculatedLineItemCollection
    {
        return new CalculatedLineItemCollection(
            array_map(
                function (DeliveryPosition $position) {
                    return $position->getItem();
                },
                $this->elements
            )
        );
    }

    /**
     * @param DeliveryPosition $element
     *
     * @return string
     */
    protected function getKey(DeliveryPosition $element): string
    {
        return $element->getIdentifier();
    }
}
