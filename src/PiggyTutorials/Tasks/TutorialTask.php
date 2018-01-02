<?php

namespace PiggyTutorials\Tasks;

use PiggyTutorials\Main;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;

/**
 * Class TutorialTask
 */
class TutorialTask extends PluginTask
{
    private $plugin;
    private $player;
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
        parent::__construct($plugin);
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        if ($this->part <= $this->plugin->getTotalTutorialMessages()) {
            $message = $this->plugin->getTutorialMessage($this->part - 1);
            $lines = explode("\n", $message);
            foreach ($lines as $k => $line){
                $lines[$k] = str_pad($line, max(array_map("strlen", $lines)), " ", STR_PAD_BOTH);
            }
            $message = implode("\n", $lines);
            var_dump($lines);
            $this->player->addTitle("", $message);
            $this->part++;
            return true;
        }
        $this->plugin->removeFromTutorialMode($this->player);
        $this->plugin->getServer()->getScheduler()->cancelTask($this->getHandler()->getTaskId());
        return false;
    }
}