<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [

            // Création d'un nouveau filtre Twig appelé "size" dans twig qui appellera la méthode "size()" un peu plus bas dans le fichier
            new TwigFilter('size', [$this, 'size']),
            new TwigFilter('excerpt', [$this, 'excerpt']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('redTitle', [$this, 'redTitle'], ['is_safe' => ['html']]),
        ];
    }


    /**
     * Méthode "branchée" sur le filtre Twig "size"
     * Cette méthode n'accepte en paramètre que des chaînes de texte et doit retourner obligatoirement un entier
     */
    public function size(string $value): int
    {

        $result = mb_strlen($value);

        return $result;
    }


    /**
     * Filtre qui retournera la chaîne de texte données tronquée à $maxSize caractères avec "..." concaténé derrière.
     * Si jamais la chaîne de texte est plus petite que la taille demandée, on la retournera sans la modifier.
     */
    public function excerpt(string $value, int $maxSize): string
    {
        if(mb_strlen($value) <= $maxSize){
            return $value;
        }

        return mb_substr($value, 0, $maxSize) . '...';

        // Même chose que les 4 lignes du dessus en plus compacte avec une ternaire :
        // return mb_strlen($value) > $maxSize ? substr($value, 0, $maxSize) . '...' : $value;
    }


    /**
     * Fonction qui permet de créer des titres en rouge.
     */
    public function redTitle(string $titleText): string
    {
        return '<h1 style="color:red;">' . htmlspecialchars($titleText) . '</h1>';
    }

}
