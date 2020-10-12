<?php


namespace Printer;


use Model\Combination;

/**
 * Class CombiPrinter
 * @package Printer
 */
class CombiPrinter
{
    /**
     * CombiPrinter constructor.
     */
    public function __construct()
    {
    }

    /**
     * Renvoie le code Html d'une combinaison
     * @param Combination $combination
     * @return string
     */
    public function print(Combination $combination): string
    {
        $partLine = '<p class="combi-tried">';
        foreach ($combination->getPaws() as $value) {
            $partLine .= '<span class="' . Combination::COLORS[$value] . '"></span>';
        }
        return $partLine . '</p>';
    }

    /**
     * Renvoie le code Html d'une combinaison pour la partie
     * @param Combination $combination
     * @param string[] $colors Couleurs possibles pour la partie
     * @return string
     */
    public function printColors(Combination $combination, array $colors): string
    {
        $line = '<p class="combi-tried">';
        foreach ($combination->getPaws() as $value) {
            for ($i = 0; $i < count($colors); $i++) {
                if ($colors[$i] === $value) {
                    $line .= '<span class="' . $colors[$i] . '"></span>';
                }
            }
        }
        return $line . '</p>';
    }

}
