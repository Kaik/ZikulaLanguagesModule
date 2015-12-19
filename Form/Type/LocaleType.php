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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocaleType extends SymfonyAbstractType implements TranslatableInterface {

    protected $domain;

    public function __construct() {
        $this->domain = 'zikula';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                // remove from language map installed languages
                //language_direction = "ltr" // ltr or rtl for left or right
                ->add('language_direction', 'choice', array(
                    'choices' => array('ltr' => $this->__('Left to right'), 'rtl' => $this->__('Right to left')),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'label' => $this->__('Language direction'),
                ))
                //firstweekday = "0"        // 0 = Sunday, 1 Monday etc.
                ->add('firstweekday', 'choice', array(
                    'choices' => array('0' => $this->__('Sunday'),
                                       '1' => $this->__('Monday'),
                                       '2' => $this->__('Tuesday'),
                                       '3' => $this->__('Wednesday'),
                                       '4' => $this->__('Thursday'),
                                       '5' => $this->__('Friday'),
                                       '6' => $this->__('Saturday'),
                        ),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'label' => $this->__('First day of the week'),
                ))
        //timeformat = "24"         // Use 12/24 depending on country
                ->add('timeformat', 'choice', array(
                    'choices' => array('12' => $this->__('12'),
                                       '24' => $this->__('24'),
                        ),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'label' => $this->__('Time mode'),
                ))
        //decimal_point = "."       // Decimal point character
                ->add('decimal_point', 'text')
        //thousands_sep = ","       // Thousands separator
                ->add('thousands_sep', 'text')
        //int_curr_symbol = USD     // International currency symbol (i.e. USD)
                ->add('int_curr_symbol', 'currency')
        //currency_symbol = "\$"     // Local currency symbol (i.e. $)
               ->add('currency_symbol', 'text')
        //mon_decimal_point = "."   // Monetary decimal point character
        ->add('mon_decimal_point', 'text')
        //mon_thousands_sep = ","   // Monetary thousands separator
        ->add('mon_thousands_sep', 'text')
        //positive_sign = ""        // Sign for positive values
        ->add('positive_sign', 'text')
        //negative_sign = "-"       // Sign for negative values
        ->add('negative_sign', 'text')
        //int_frac_digits = "2"     // International fractional digits
        ->add('int_frac_digits', 'text')
        //frac_digits = "2"         // Local fractional digits
        ->add('frac_digits', 'text')
        //; TRUE if currency_symbol precedes a positive value, FALSE if it succeeds one
        //p_cs_precedes = "1"   
        ->add('p_cs_precedes', 'text')  
        //; TRUE if a space separates currency_symbol from a positive value, FALSE otherwise
        //p_sep_by_space = "0"  
        ->add('p_sep_by_space', 'text')  
        //; TRUE if currency_symbol precedes a negative value, FALSE if it succeeds one
        //n_cs_precedes = "1"
        ->add('n_cs_precedes', 'text')     
        //; TRUE if a space separates currency_symbol from a negative value, FALSE otherwise
        //n_sep_by_space = "0" 
        ->add('n_sep_by_space', 'text')   
        //; 0 - Parentheses surround the quantity and currency_symbol
        //; 1 - The sign string precedes the quantity and currency_symbol
        //; 2 - The sign string succeeds the quantity and currency_symbol
        //; 3 - The sign string immediately precedes the currency_symbol
        //; 4 - The sign string immediately succeeds the currency_symbol
        //p_sign_posn = "1"
        ->add('p_sign_posn', 'text')        
        //; 0 - Parentheses surround the quantity and currency_symbol
        //; 1 - The sign string precedes the quantity and currency_symbol
        //; 2 - The sign string succeeds the quantity and currency_symbol
        //; 3 - The sign string immediately precedes the currency_symbol
        //; 4 - The sign string immediately succeeds the currency_symbol
        //n_sign_posn = "1"
        ->add('n_sign_posn', 'text')
        //; Array containing numeric groupings
        //grouping = "3,3"    
        ->add('grouping', 'text')    
        //; Array containing monetary groupings    
        //mon_grouping = "3,3"
        ->add('mon_grouping', 'text')
        ;

        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', [
                'label' => $this->__('Save'),
                'attr' => [
                    'class' => 'btn-success'
                ]
            ]);
        }
    }

    /**
     * OptionsResolverInterface is @deprecated and is supposed to be replaced by
     * OptionsResolver but docs not clear on implementation
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => true,
        ));
    }

    public function getName() {
        return 'zikulalanguagesmodule_localetype';
    }

    /**
     * singular translation for modules.
     *
     * @param string $msg Message.
     *
     * @return string
     */
    public function __($msg) {
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
    public function _n($m1, $m2, $n) {
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
    public function __f($msg, $param) {
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
    public function _fn($m1, $m2, $n, $param) {
        return _fn($m1, $m2, $n, $param, $this->domain);
    }

}
