<?php
/**
 * Languages Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

namespace Zikula\LanguagesModule\Controller;

use Zikula\Core\Controller\AbstractController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Theme\Annotation\Theme;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{ 
    
    /**
     * @Route("/config", options={"i18n"=false})
     *
     * @Theme("admin")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception of various types
     */
    public function configAction(Request $request)
    {
        //legacy
        $legacy = array();
        $legacy['translator'] = \ZLanguage::getInstance();
        $legacy['translator']->bindModuleDomain($this->name);
        $legacy['locale'] = \ZLanguage::getLocale();
        
        $legacy['installed_languages'] = \ZLanguage::getInstalledLanguages();
		$installed_languages = array();
		foreach($legacy['installed_languages'] as $langCode){
			$installed_languages[$langCode]['name'] = \ZLanguage::getLanguageName($langCode);
			$installed_languages[$langCode]['data'] = new \ZLocale($langCode); 
			
		}
        
        
        
        //symfony
        $symfony['translator'] = $this->container->get('translator');
        $symfony['locale'] = $symfony['translator']->getLocale();

        
        $settings = array('mlsettings_language_i18n' => 'en',
        		'mlsettings_multilingual' => 1,
        		'mlsettings_language_detect' => 0,
        		'mlsettings_languageurl' => 0);
        
        
        $form = $this->createForm('zikulalanguagesmodule_settingstype', $settings, array(
        	//	'action' => $this->generateUrl('target_route'),
        		'method' => 'GET',
        ));
        
        /**

        $form->handleRequest($request);
        if ($form->isValid()) {
        	$data = $form->getData();
        	foreach ($data as $name => $value) {
        		//\ModUtil::setVar('CmfcmfMediaModule', $name, $value);
        	}
        	$this->addFlash('status', $this->__('Settings saved!'));
        }
    	**/
        

        
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('ZikulaLanguagesModule:Admin:config.html.twig',
            array('form' => $form->createView(),
                'legacy' => $legacy,
            	'installed_languages' => $installed_languages,
                'symfony' => $symfony
            ));
    }
    
    /**
     * @Route("/newlanguage", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return AjaxResponse
     */
    public function newlanguageAction(Request $request)
    {
        
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('ZikulaLanguagesModule:Admin:newlanguage.html.twig',
            array('test' => 'tescik'
            ));
    }    
}