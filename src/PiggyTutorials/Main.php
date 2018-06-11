<?php

namespace PiggyTutorials;

use PiggyTutorials\Commands\TutorialCommand;
use PiggyTutorials\Tasks\TutorialTask;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

/**
 * Class Main
 * @package PiggyTutorials
 */
class Main extends PluginBase
{
    private $tutorialMode;
    private $tutorialMessages;
    private $tutorialDelay;

    public function onEnable()
    {
        $this->saveDefaultConfig();

        $this->tutorialMessages = $this->getConfig()->getNested("tutorial.messages");
        $this->tutorialDelay = $this->getConfig()->getNested("tutorial.delay");

        $this->getServer()->getCommandMap()->register("tutorial", new TutorialCommand("tutorial", $this));

        $this->getLogger()->info(TextFormat::GREEN . "Enabled.");
    }

    public function startTutorial(Player $player)
    {
        $task = new TutorialTask($this, $player);
        $handler = $this->getScheduler()->scheduleRepeatingTask($task, $this->tutorialDelay * 20);
        $task->setHandler($handler);
        $this->addToTutorialMode($player);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function addToTutorialMode(Player $player)
    {
        if (!isset($this->tutorialMode[$player->getLowerCaseName()])) {
            $this->tutorialMode[$player->getLowerCaseName()] = true;
            return true;
        }
        return false;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function removeFromTutorialMode(Player $player)
    {
        if (isset($this->tutorialMode[$player->getLowerCaseName()])) {
            unset($this->tutorialMode[$player->getLowerCaseName()]);
            return true;
        }
        return false;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isInTutorialMode(Player $player)
    {
        return isset($this->tutorialMode[$player->getLowerCaseName()]) ? $this->tutorialMode[$player->getLowerCaseName()] : false;
    }

    /**
     * @return array
     */
    public function getTutorialMessages()
    {
        return $this->tutorialMessages;
    }

    public function getTutorialMessage(int $part)
    {
        return isset($this->tutorialMessages[$part]) ? $this->tutorialMessages[$part] : null;
    }

    /**
     * @return int
     */
    public function getTotalTutorialMessages()
    {
        return count($this->tutorialMessages);
    }

    /**
     * @param array $messages
     */
    public function setTutorialMessages(array $messages)
    {
        $this->tutorialMessages = $messages;
    }

    public function setTutorialMessage(string $message, int $part)
    {
        $this->tutorialMessages[$part] = $message;
    }
}