<?php

/**
 * Languages Module for Zikula
 *
 * @copyright  Languages Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */
/**
 * Languages module installer.
 */

namespace Zikula\LanguagesModule;

use Zikula\Core\AbstractExtensionInstaller;

class LanguagesModuleInstaller extends AbstractExtensionInstaller {

    public function install() {
        $this->setVars(['installed' => 'en']);
        return true;
    }

    public function uninstall() {
        // Delete any module variables
        $this->delVars();
        return true;
    }

    public function upgrade($oldVersion) {
        return true;
    }

}
