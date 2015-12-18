<?php
/**
 * Copyright Languages Team 2015
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package InterCom
 * @link https://github.com/zikula-modules/Pages
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\LanguagesModule\Container;

use SecurityUtil;
use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\Translator;
use Zikula\Core\LinkContainer\LinkContainerInterface;
use Zikula\ExtensionsModule\Api\VariableApi;

class LinkContainer implements LinkContainerInterface
{
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var VariableApi
     */
    private $settings;    

    public function __construct(RouterInterface $router, $translator, VariableApi $settings)
    {
        $this->translator = $translator;
        $this->router = $router;
        $this->settings = $settings;
    }
    
    /**
     * get Links of any type for this extension
     * required by the interface
     *
     * @param string $type
     * @return array
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
    	$method = 'get' . ucfirst(strtolower($type));
    	if (method_exists($this, $method)) {
    		return $this->$method();
    	}
    
    	return array();
    }
    
    /**
     * get the Admin links for this extension
     *
     * @return array
     */
    private function getAdmin()
    {
    	$links = array();
            if (SecurityUtil::checkPermission('ZikulaLanguages::', '::', ACCESS_ADMIN)) {
        		$links[] = array(
        				'url' => $this->router->generate('zikulalanguagesmodule_admin_config'),
        				'text' => $this->translator->__('Manage languages'),
        				'title' => $this->translator->__('Manage installed languages'),
        				'icon' => 'fa fa-dashboard');
            }
    	return $links;
    }
    
    /**
     * get the User Links for this extension
     *
     * @return array
     */
    private function getUser()
    {
    	$links = array();
    	return $links;
    }    

    public function getBundleName()
    {
        return 'ZikulaLanguagesModule';
    }
}