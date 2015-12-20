<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zikula\LanguagesModule\Manager;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Description of Languages
 *
 * @author Kaik
 */
class Languages {

    /**
     * Language for this request.
     *
     * @var string
     */
    public $core;

    /**
     * Language for this request.
     *
     * @var string
     */
    public $translator;

    /**
     * Language for this request.
     *
     * @var string
     */
    public $installed;

    /**
     * Language for this request.
     *
     * @var string
     */
    public $parentDir;

    /**
     * Language for this request.
     *
     * @var string
     */
    public $dirAccess;

    public function __construct($translator) {
        $this->core = \ZLanguage::getInstance();
        $this->core->bindModuleDomain('ZikulaLanguagesModule');
        $this->translator = $translator;
        $this->setInstalled();
        $this->parentDir = 'app/Resources/locale';
        $this->dirAccess = is_writable('app/Resources/locale');
    }

    public function setInstalled() {

        $installed_languages = \ZLanguage::getInstalledLanguages();
        $installed = [];
        foreach ($installed_languages as $langCode) {
            $installed[$langCode]['name'] = \ZLanguage::getLanguageName($langCode);
            $installed[$langCode]['data'] = new \ZLocale($langCode);
        }
        $this->installed = $installed;
        return;
    }

    public function getInstalled() {
        return $this->installed;
    }

    public function getDirAccess() {
        return $this->dirAccess;
    }

    public function setLanguage($data) {
        $language_code = $data['language_code'];
        $language_data_obj = $data['locale'][0];
        return $this->setLanguageIniFile($language_code, $language_data_obj);
    }
    
    public function setLanguageIniFile($language_code, $lang_data) {
        $fs = new Filesystem();
        return $fs->dumpFile($this->parentDir . '/' . $language_code . '/locale.ini', $this->getIniFromArray($lang_data));
    }    

    public function getIniFromArray($ini_array) {
        $content = "";
        foreach ($ini_array as $key => $elem) {
            if (is_array($elem)) {
                for ($i = 0; $i < count($elem); $i++) {
                    $content .= $key . "[] = \"" . $elem[$i] . "\"\n";
                }
            } else if ($elem == ""){
                $content .= $key . " = \n";
            }else{
                $content .= $key . " = \"" . $elem . "\"\n";
            }       
            }
        return $content;
    }

    public function getAddLanguageSelectData($all = false) {
        $languages_map = \ZLanguage::languageMap();
        if ($all){
            return $languages_map; 
        }else{
            return array_diff_key($languages_map, $this->installed);   
        }
    }

    public function getDefaultIniData() {
        return parse_ini_file($this->parentDir . '/en/locale.ini');
    }

    public function getLanguageData($lang) {
        return parse_ini_file($this->parentDir . '/' . $lang . '/locale.ini');
    }
}
