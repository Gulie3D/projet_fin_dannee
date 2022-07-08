<?php
namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class, [ //extension TextType permettra de changer le visuel du formulaire 
                'label'     => false,  
                'required'  => false,
                'attr'      => [
                    'placeholder' => 'Votre recherche ...',
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('categories', EntityType::class, [ //permet de lié le resultat de recherche à l'entité category
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'multiple' => true, //permet de selectionner plusieurs valeur
                'expanded' =>true, //vue en chexbox
            ]) 
            ->add('submit', SubmitType::class,[
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET', //les info transitent par l'url
            'crsf_protection' => false, //désactive la crsf_protection

        ]);
    }

    public function getBlockPrefix()
    { //retourne rien 
        return '';
    }
}