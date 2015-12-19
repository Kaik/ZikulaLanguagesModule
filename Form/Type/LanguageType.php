<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
  *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\LanguagesModule\Form\Type;

use Symfony\Component\Form\AbstractType as SymfonyAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Zikula\Common\I18n\TranslatableInterface;


class LanguageType extends SymfonyAbstractType implements TranslatableInterface
{
    protected $domain;
    
    public function __construct()
    {
        $this->domain = 'zikula';
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('mlsettings_multilingual', 'choice', array(
        		'choices' => \ZLanguage::countryMap(),
        		'multiple' => false,
        		'expanded' => false,
        		'required' => true
        	))                
            ->add('save', 'submit', [
                'label' => $this->__('Save'),
                'attr' => [
                    'class' => 'btn-success'
                ]
            ])
        ;
    }
    public function getName()
    {
        return 'zikulalanguagesmodule_languagetype';
    }
    /**
     * singular translation for modules.
     *
     * @param string $msg Message.
     *
     * @return string
     */
    public function __($msg)
    {
        return __($msg, $this->domain);
    }
    /**
     * Plural translations for modules.
     *
     * @param string $m1 Singular.
     * @param string $m2 Plural.
     * @param int    $n  Count.
     *
     * @return string
     */
    public function _n($m1, $m2, $n)
    {
        return _n($m1, $m2, $n, $this->domain);
    }
    /**
     * Format translations for modules.
     *
     * @param string       $msg   Message.
     * @param string|array $param Format parameters.
     *
     * @return string
     */
    public function __f($msg, $param)
    {
        return __f($msg, $param, $this->domain);
    }
    /**
     * Format pural translations for modules.
     *
     * @param string       $m1    Singular.
     * @param string       $m2    Plural.
     * @param int          $n     Count.
     * @param string|array $param Format parameters.
     *
     * @return string
     */
    public function _fn($m1, $m2, $n, $param)
    {
        return _fn($m1, $m2, $n, $param, $this->domain);
    }
}