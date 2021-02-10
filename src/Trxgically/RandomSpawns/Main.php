<?php

namespace Trxgically\RandomSpawns;
use pocketmine\{Server, Player};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\Listener;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerJoinEvent;


class Main extends PluginBase implements Listener
{
    private $config;


    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->config->getAll();
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {

        if ($cmd->getName() === "randomspawns") {
            if ($sender->hasPermission("randomspawns.perms")){
                if (count($args) === 0) {
                    $sender->sendMessage(TF::DARK_RED . TF::BOLD . "RandomSpawns Commands :" . TF::RESET . "\n" . "\n" . TF::RED . "/randomspawns setradius" . TF::GRAY . " - Set the radius for player spawns!");
                } else {
                    switch ($args[0]) {
                        case "setradius":
                            if (count($args) === 4) {
                                $xt = $args[1];
                                $zt = $args[2];
                                $world = $args[3];

                                $this->config->setNested("radius.x", $xt);
                                $this->config->setNested("radius.z", $zt);
                                $this->config->setNested("world", $world);

                                $this->config->setNested("set.world", true);
                                $this->config->setNested("set.radius", true);
                                $this->config->save();
                                $sender->sendMessage("Radius set!");
                            } else {
                                $sender->sendMessage("Format: x,x z,z worldname");
                            }
                    }
                }
            }
        }

        return true;

    }

    public function onPlayerRespawn(PlayerRespawnEvent $e) {
        if($this->config->getNested("set.world") && $this->config->getNested("set.radius") === true){
        $w = $this->config->getNested("world");
        $world = $this->getServer()->getLevelByName($w);
        $name = $e->getPlayer();

        $x = $this->config->getNested("radius.x");
        $x1 = explode(",", $x);
        $z = $this->config->getNested("radius.z");
        $z1 = explode(",", $z);

        $xfinal = mt_rand($x1[0], $x1[1]);
        $zfinal = mt_rand($z1[0], $z1[1]);
        $y = $name->getFloorY() + 2;
        
        $world->loadChunk($xfinal,$zfinal);
        $e->setRespawnPosition(new Position($xfinal,$y,$zfinal,$world));
        $name->sendMessage("Player teleported!");
        }
    }

    public function playerJoinEvent(PlayerJoinEvent $e) {

        $name = $e->getPlayer();

        if($this->config->getNested("set.world") && $this->config->getNested("set.radius") === true){
            if($name->hasPlayedBefore() === false) {
                $w = $this->config->getNested("world");
                $world = $this->getServer()->getLevelByName($w);
                $x = $this->config->getNested("radius.x");
                $x1 = explode(",", $x);
                $z = $this->config->getNested("radius.z");
                $z1 = explode(",", $z);

                $xfinal = mt_rand($x1[0], $x1[1]);
                $zfinal = mt_rand($z1[0], $z1[1]);
                $y = $name->getFloorY() + 2;
        
                $world->loadChunk($xfinal,$zfinal);
                $name->teleport(new Position($xfinal,$y,$zfinal,$world));
                $name->sendMessage("New! Teleported to a random location!");
        }
    }
}


}
