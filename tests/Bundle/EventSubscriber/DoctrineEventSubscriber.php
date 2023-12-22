<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Metadata\Test\Bundle\EventSubscriber;

use Splash\Bundle\Helpers\Doctrine\AbstractEventSubscriber;
use Splash\Metadata\Test\Bundle\Entity;

class DoctrineEventSubscriber extends AbstractEventSubscriber
{
    /**
     * {@inheritdoc}
     */
    protected static array $classMap = array(
        Entity\ShortEntity::class => "ShortEntity",
        Entity\SimpleEntity::class => "SimpleEntity",
        Entity\OneToOneEntity::class => "OneToOneEntity",
        Entity\OneToManyEntity::class => "OneToManyEntity",
    );
}
