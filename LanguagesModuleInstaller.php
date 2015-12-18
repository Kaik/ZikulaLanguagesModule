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
 * Intercom module installer.
 */

namespace Zikula\LanguagesModule;

use Zikula\Core\AbstractBundle;
use Zikula\Core\ExtensionInstallerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LanguagesModuleInstaller implements ExtensionInstallerInterface, ContainerAwareInterface
{
		
	/**
	 * @var \
	 */
	private $request;
	/**
	 * @var \
	 */
	private $entityManager;
	
	public function __construct()
	{
		$this->request = \ServiceUtil::get('request');	
	}
	
	public function setBundle(AbstractBundle $bundle)
	{
		$this->bundle = $bundle;
	}
	
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
		$this->entityManager = $this->container->get('doctrine.entitymanager');
	}

	public function install()
	{
            $vars = array('installed' => 'en');
            $this->container->get('zikula_extensions_module.api.variable')->setAll('ZikulaLanguagesModule', $vars);
        return true;
	}

        public function uninstall()
        {
        // Delete any module variables
        $this->container->get('zikula_extensions_module.api.variable')->delAll('ZikulaLanguagesModule');
        return true;
        }

        public function upgrade($oldVersion) {

        }

}
