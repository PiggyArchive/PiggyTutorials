<?php

namespace DaPigGuy\PiggyTutorials;

use DaPigGuy\PiggyTutorials\Commands\TutorialCommand;
use DaPigGuy\PiggyTutorials\Tasks\TutorialTask;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

/**
 * Class Main
 * @package DaPigGuy\PiggyTutorials
 */
class Main extends PluginBase
{
    /** @var array */
    private $tutorialMode;
    /** @var array */
    private $tutorialMessages;
    /** @var int */
    private $tutorialDelay;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->tutorialMessages = $this->getConfig()->getNested("tutorial.messages");
        $this->tutorialDelay = $this->getConfig()->getNested("tutorial.delay");
        $this->getServer()->getCommandMap()->register("piggytutorials", new TutorialCommand("tutorial", $this));
    }

    /**
     * @param Player $player
     */
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

    /**
     * @param int $part
     * @return string|null
     */
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

    /**
     * @param string $message
     * @param int    $part
     */
    public function setTutorialMessage(string $message, int $part)
    {
        $this->tutorialMessages[$part] = $message;
    }
}