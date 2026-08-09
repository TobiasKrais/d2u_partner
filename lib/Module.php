<?php
namespace TobiasKrais\D2UPartner;
/**
 * Class managing modules published by www.design-to-use.de.
 *
 * @author Tobias Krais
 */
class Module
{
    /**
     * Get modules offered by this addon.
     * @return \TobiasKrais\D2UHelper\Module[] Modules offered by this addon
     */
    public static function getModules()
    {
        $modules = [];
        $modules[] = new \TobiasKrais\D2UHelper\Module('25-1',
            'D2U Business Partner - Business Partner (BS4, deprecated)',
            1);
        $modules[] = new \TobiasKrais\D2UHelper\Module('25-2',
            'D2U Business Partner - Business Partner (BS5)',
            1);
        return $modules;
    }
}
