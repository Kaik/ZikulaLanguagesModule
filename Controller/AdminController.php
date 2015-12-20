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

        if (!$this->hasPermission('ZikulaLanguagesModule::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
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
                    'manager' => $this->get('zikula_languages_module.manager.languages')
        ));
    }

    /**
     * @Route("/editlanguage", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return AjaxResponse
     */
    public function editlanguageAction(Request $request) {

        if (!$this->hasPermission('ZikulaLanguagesModule::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        $language_code = $request->get('language_code', false);
        $language = ['language_code' => $language_code];
        if($language_code === false){
           $language['locale'] = [$this->get('zikula_languages_module.manager.languages')->getDefaultIniData()];          
        }else{
           $language['locale'] = [$this->get('zikula_languages_module.manager.languages')->getLanguageData($language_code)];          
        }
        
        $form = $this->createForm('zikulalanguagesmodule_languagetype', $language, array(
            //'action' => $this->generateUrl('target_route'),
            'method' => 'POST',
            'mode' => ($language_code === false) ? 'add' : 'edit',
            'isXmlHttpRequest' => $request->isXmlHttpRequest(),
        ));
        if ($request->getMethod() == "POST") {
            $status['ispost'] = true;
            $form->handleRequest($request);
            //if ($form->isValid()) {
                $data = $form->getData();
                $status['data'] = $this->get('zikula_languages_module.manager.languages')->setLanguage($data);
                //$this->addFlash('status', $this->__('Settings saved!'));
           // }
            return new JsonResponse(array('status' => $status));
        } else {
            $request->attributes->set('_legacy', true); // forces template to render inside old theme
            return $this->render('ZikulaLanguagesModule:Admin:newlanguage.html.twig', array('form' => $form->createView()
            ));
        }
    }

    /**
     * @Route("/removelanguage", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return AjaxResponse
     */
    public function removelanguageAction(Request $request) {

        if (!$this->hasPermission('ZikulaLanguagesModule::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        $lang_to_remove = $request->request->get('language_code', null);
        $status['data'] = $this->get('zikula_languages_module.manager.languages')->removeLanguage($lang_to_remove);
        return new JsonResponse(array('status' => $status));
    }

}
