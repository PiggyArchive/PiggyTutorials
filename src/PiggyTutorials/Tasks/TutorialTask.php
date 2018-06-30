<?php

namespace PiggyTutorials\Tasks;

use PiggyTutorials\Main;
use pocketmine\Player;
use pocketmine\scheduler\Task;

/**
 * Class TutorialTask
 */
class TutorialTask extends Task
{
    /** @var Main */
    private $plugin;
    /** @var Player */
    private $player;
    /** @var int */
    private $part = 1;


    /**
     * TutorialTask constructor.
     * @param Main $plugin
     * @param Player $player
     */
    public function __construct(Main $plugin, Player $player)
    {
        $this->plugin = $plugin;
        $this->player = $player;
    }

    /**
     * @param int $currentTick
     * @return bool
     */
    public function onRun(int $currentTick)
    {
        if ($this->part <= $this->plugin->getTotalTutorialMessages()) {
            $message = $this->plugin->getTutorialMessage($this->part - 1);
            $lines = explode("\n", $message);
            foreach ($lines as $k => $line) {
                $lines[$k] = str_pad($line, max(array_map("strlen", $lines)), " ", STR_PAD_BOTH);
            }
            $message = implode("\n", $lines);
            $this->player->addTitle("", $message);
            $this->part++;
            return true;
        }
        $this->plugin->removeFromTutorialMode($this->player);
        $this->plugin->getScheduler()->cancelTask($this->getHandler()->getTaskId());
        return false;
    }
}