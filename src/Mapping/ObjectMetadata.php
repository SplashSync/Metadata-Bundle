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

namespace Splash\Metadata\Mapping;

class ObjectMetadata
{
    public ?string $type = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $ico = null;
    public ?bool $allowPushCreated = null;
    public ?bool $allowPushUpdated = null;
    public ?bool $allowPushDeleted = null;
    public ?bool $enablePushUpdated = null;
    public ?bool $enablePushDeleted = null;
    public ?bool $enablePullCreated = null;
    public ?bool $enablePullUpdated = null;
    public ?bool $enablePullDeleted = null;

    /**
     * @param class-string $class
     */
    public function __construct(
        private readonly string $class,
    ) {
    }

    /**
     * Get Target Class
     *
     * @return class-string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
