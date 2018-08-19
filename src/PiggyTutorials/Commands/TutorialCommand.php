<?php

namespace PiggyTutorials\Commands;

use PiggyTutorials\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class TutorialCommand
 * @package PiggyTutorials\Commands
 */
class TutorialCommand extends PluginCommand
{
    /**
     * TutorialCommand constructor.
     * @param string $name
     * @param Main $plugin
     */
    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name, $plugin);
        $this->setDescription("Go through the tutorial");
        $this->setUsage("/tutorial");
        $this->setPermission("piggytutorials.command.tutorial");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$this->testPermission($sender)) return;
        $plugin = $this->getPlugin();
        if ($plugin instanceof Main) {
            if ($sender instanceof Player) {
                if (!$plugin->isInTutorialMode($sender)) {
                    $plugin->startTutorial($sender);
                    return;
                }
                $sender->sendMessage(TextFormat::RED . "You are already in the tutorial.");
                return;
            }
            $sender->sendMessage(TextFormat::RED . "Use this in-game.");
        }
    }
}