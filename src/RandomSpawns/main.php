<?php

namespace Trxgically\XpBottles;
use pocketmine\{Server, Player};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\Listener;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\utils\Config;
use pocketmine\item\ItemFactory;


class Main extends PluginBase implements Listener
{
    private $config;


    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TF::GREEN . "XpBottles enabled!");
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->config->getAll();
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {

        if ($cmd->getName() === "xpbottles") {
            if (count($args) === 0) {
                $sender->sendMessage(TF::DARK_RED . TF::BOLD . "Airdrop Commands :" . TF::RESET . "\n" . "\n" . TF::RED . "/airdrops start" . TF::GRAY . " - Start an Airdrop event" . "\n" . TF::RED . "/airdrops stop" . TF::GRAY . " - Stop an Airdrop event" . "\n" . TF::RED . "/airdrops setspawn" . TF::GRAY . " - Set the player spawnpoint for joining an event" . "\n" . TF::RED . "/airdrops setchest" . TF::GRAY . " - Set an Airdrop chest!" . "\n" . TF::RED . "/airdrops setjoinmessage" . TF::GRAY . " - Set the Airdrop player join message");
            } elseif (count($args) === 1) {
                switch ($args[0]) {

                    case "1":
                        $sender->sendMessage("1")
                        break;

                }
            }
       
        }

        return true;

    }

}
