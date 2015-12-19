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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Theme\Annotation\Theme;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController {

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
    public function configAction(Request $request) {
        // convert to class
        $languages_manager = [];
        //get intance
        $languages_manager['core'] = \ZLanguage::getInstance();
        //bind domain
        $languages_manager['core']->bindModuleDomain($this->name);
        //locale cuurent detected
        $languages_manager['locale'] = \ZLanguage::getLocale();
        //installed languages + data              
        $installed_languages = \ZLanguage::getInstalledLanguages();
        $langs_data = [];
        foreach ($installed_languages as $langCode) {
            $langs_data[$langCode]['name'] = \ZLanguage::getLanguageName($langCode);
            $langs_data[$langCode]['data'] = new \ZLocale($langCode);
        }
        $languages_manager['installed'] = $langs_data;
        //symfony
        $languages_manager['translator'] = $this->container->get('translator');
        $languages_manager['dir_access'] = is_writable('app/Resources/locale');
        // ml settings todo add real settings getter
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
         * */
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('ZikulaLanguagesModule:Admin:config.html.twig', array('form' => $form->createView(),
                    'manager' => $languages_manager
        ));
    }

    /**
     * @Route("/newlanguage", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return AjaxResponse
     */
    public function newlanguageAction(Request $request) {

        //use as default data 
        $eng_ini = parse_ini_file('app/Resources/locale/en/locale.ini');
        
        //var_dump($eng_ini);
        //exit(0);
        
        $data = array('locale' => array($eng_ini));
        
        $form = $this->createForm('zikulalanguagesmodule_languagetype', $data, array(
            //	'action' => $this->generateUrl('target_route'),
            'method' => 'GET',
            'isXmlHttpRequest' => $request->isXmlHttpRequest(),
        ));

        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('ZikulaLanguagesModule:Admin:newlanguage.html.twig', array('form' => $form->createView()
        ));
    }

    /**
     * @Route("/createlanguage", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return AjaxResponse
     */
    public function createlanguageAction(Request $request) {

        $languageToAdd = $request->request->get('language', null);
               
        // add language check need to be 2 letters
        if ($languageToAdd) {

            $fs = new Filesystem();
            $errors = [];
            try {
                $fs->mkdir('app/Resources/locale/' . $languageToAdd);
            } catch (IOExceptionInterface $e) {
                $errors[] =  "An error occurred while creating your directory at " . $e->getPath();
            }
        }

        return new JsonResponse(array('language' => $languageToAdd,'errors' => $errors,'def_ini' => $eng_ini));
    }

}
