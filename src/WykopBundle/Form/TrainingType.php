<?php

namespace WykopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{    
    public function __construct($default_tag = 0, $ad = false) {
	
	$this->default_tag = $default_tag;
	$this->ad = $ad;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$years = array();
	
	if(date('z') < 7 )
	    $years[] = date('Y') + 1;
	
	$years[] = date('Y');
	
	if(date('z') > 358 )
	    $years[] = date('Y') + 1;
	
	
	
	$months = array();
	
	if(date('j') < 7 )
	    $months[] = date('n') - 1;
	
	$months[] = date('n');
	
	if(date('j') > 20 )
	    $months[] = date('n') + 1;
	
	
	
	$from = date('j')-7;
	$to = date('j');
	
	$days_last_month = 31;
	$days_this_month = 31;
	$days_next_month = 31;
	
	$days_last_month = date('t', mktime(0, 0, 0, date('n') - 1, 1, date('Y'))); 
	$days_this_month = date('t', mktime(0, 0, 0, date('n'), 1, date('Y'))); 
	$days_next_month = date('t', mktime(0, 0, 0, date('n')+1, 1, date('Y'))); 
	
	for($i = $from; $i <= $to; $i++){
	    if($i <= 0)
		$days[] = $days_last_month + $i;
	    else
		$days[] = $i;
	}
	
	$date = new \DateTime();
	$minutes = $date->format('i');
	if($minutes > 0){
	    //$date->modify("+- hour");
	    $date->modify('-'.$minutes.' minutes');
	}
	
        $builder
            ->add('Tag', 'entity', array(
		'label' => false,
		'placeholder' => 'Wybierz tag',
		'class' => 'WykopBundle:Tag',
		'data' => $this->default_tag,
		'attr' => array(
		    'oninvalid' => 'InvalidMsg(this);'
		    )
		))
            ->add('City', 'entity', array(
		'required' => false, 
		'label' => false, 
		'placeholder' => 'Wybierz miasto',
		'class' => 'WykopBundle:City'
		))
            ->add('nameUser', 'hidden', array(
		'label' => 'Login'
		))
            ->add('distance', 'collection', array(
		'type' => 'text',
		'allow_add' => true,
		'prototype' => true,
		'required' => true,
		'label' => false,
		'options' => array(
		    'required' => true
		)
		))
	    ->add('dates', 'collection', array(
		'type' => 'datetime',
		'allow_add' => true,
		'prototype' => true,
		'mapped' => true,
		'label' => false,
		'options' => array(
		    'date_format' => \IntlDateFormatter::FULL,
		    'data' => $date,
		    'days' => $days,
		    'months' => $months,
		    'years' => $years
		    )
	    ))
	    ->add('details', 'textarea', array(
		'required' => false,
		'label' => false,
		'attr' => array(
		    'placeholder' => 'Miejsce na opis'
		    )
		))
	    ->add('ad', 'checkbox', array(
		'required' => false,
		'label' => 'Reklama na mikroblogu?',
		'attr' => array(
		    'checked' => $this->ad
		)
	    ));
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WykopBundle\Entity\Training'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wykopbundle_training';
    }
}
