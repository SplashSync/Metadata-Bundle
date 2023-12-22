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

namespace Splash\Metadata\Models\Metadata;

trait ManualAccessTrait
{
    //====================================================================//
    // BASIC ACCESS METHODS
    //====================================================================//

    /**
     * This Property is not Allowed for Metadata Reading
     */
    private ?bool $manualRead = null;

    /**
     * Function to use as Property Setter
     */
    private ?bool $manualWrite = null;

    /**
     * Check if This Property is not Allowed for Metadata Reading
     */
    public function isAllowedRead(): bool
    {
        return !($this->manualRead ?? false);
    }

    /**
     * Check if This Property is not Allowed for Metadata Writing
     */
    public function isAllowedWrite(): bool
    {
        return !($this->manualWrite ?? false);
    }

    public function setManualAccess(?bool $read, ?bool $write): static
    {
        $this->manualRead = $read ?? $this->manualRead;
        $this->manualWrite = $write ?? $this->manualWrite;
        //====================================================================//
        // Apply Rules to Child Fields
        foreach ($this->getChildren() as $child) {
            $child->setManualAccess($read, $write);
        }

        return $this;
    }
}
