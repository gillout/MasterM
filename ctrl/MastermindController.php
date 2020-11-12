<?php


namespace Ctrl;


use Core\Ctrl\Controller;
use Core\Html\Form;
use Core\Util\ErrorManager;
use Manager\MastermindManager;
use Model\CombiComparator;
use Model\Combination;
use Model\Mastermind;
use Model\RandomCombiGenerator;

/**
 * Class MastermindController
 * @package Ctrl
 */
class MastermindController extends Controller
{

    /**
     * @var MastermindManager
     */
    private $mastermindManager;

    /**
     * MastermindController constructor.
     * @param string $template
     */
    public function __construct(string $template = ROOT_DIR . 'view/template.php')
    {
        $this->mastermindManager = new MastermindManager();
        parent::__construct($template);
    }

    /**
     * Affiche la page d'accueil du jeu
     * @param Form $form
     */
    public function home(): void
    {
        $form = new Form();
        $this->render(ROOT_DIR . 'view/accueil.php', compact('form'));
    }

    /**
     * Affiche la page de démarrage du jeu
     * @param Form $form
     */
    public function start(Form $form): void
    {
        $size = $form->getValue('size') + 2;
        $level = $form->getValue('level');
        if ($size != null && $level != null) {
            $mastermind = new Mastermind();
            $mastermind->setSize($size);
            $mastermind->setLevel($level - 1);
            $generator = new RandomCombiGenerator();
            $mastermind->setColors(new Combination(Mastermind::LEVELS[$level]));
            $mastermind->setSolution($generator->generate($size, Mastermind::LEVELS[$level], true));
            $this->mastermindManager->save($mastermind);
        }
        $form = new Form();
        $this->render(ROOT_DIR . 'view/play.php', compact('mastermind', 'form'));
    }

    /**
     * Affiche la page principale du jeu
     * @param Form $form
     * @return void
     */
    public function play(Form $form): void
    {
        $mastermind = $this->mastermindManager->find();
        if ($mastermind) {
            $combination = [];
            for ($i = 0; $i < $mastermind->getSize(); $i++) {
                $combination[] = $form->getValue('numColor' . $i);
            }
            $mastermind->addProposition(new Combination($combination));
            $this->mastermindManager->save($mastermind);
            $compareResults = [];
            $comparator = new CombiComparator($mastermind->getSolution());
            foreach ($mastermind->getPropositions() as $proposition) {
                $compareResults[] = $comparator->compare($proposition);
            }
            $form = new Form();
            $this->render(ROOT_DIR . 'view/play.php', compact('mastermind', 'compareResults', 'form'));
        } else {
            ErrorManager::add('La partie n\'a pas encore été créé !');
            header ('Location: index.php');
        }
    }

}