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
        $this->getConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getConfig->getAll();
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {

        if ($cmd->getName() === "randomspawns") {
            if ($sender->hasPermission("randomspawns.perms")){
            if (count($args) === 0) {
                $sender->sendMessage(TF::DARK_RED . TF::BOLD . "RandomSpawns Commands :" . TF::RESET . "\n" . "\n" . TF::RED . "/randomspawns setradius" . TF::GRAY . " - Set the radius for player spawns!");
            } elseif (count($args) === 1) {
                switch ($args[0]) {

                    case "setradius":
                    $sender->sendMessage("Format: x,x z,z worldname");
                    break;

                }
            }elseif (count($args) === 4){
                switch ($args[0]) {

                    case "setradius":
                    if(isset($args[1]) && isset($args[2]) && isset($args[3])) {
                    $xt = $args[1];
                    $zt = $args[2];
                    $world = $args[3];

                    $this->getConfig->setNested("radius.x", $xt);
                    $this->getConfig->setNested("radius.z", $zt);
                    $this->getConfig->setNested("world", $world);

                    $this->getConfig->setNested("set.world", true);
                    $this->getConfig->setNested("set.radius", true);
                    $this->getConfig->save();
                    $sender->sendMessage("Radius set!");
                    }
                    break;

                }
            }
        }
       
        }

        return true;

    }

    public function onPlayerRespawn(PlayerRespawnEvent $e) {
        if($this->getConfig->getNested("set.world") && $this->getConfig->getNested("set.radius") === true){
        $w = $this->getConfig->getNested("world");
        $world = $this->getServer()->getLevelByName($w);
        $name = $e->getPlayer();

        $x = $this->getConfig->getNested("radius.x");
        $x1 = explode(",", $x);
        $z = $this->getConfig->getNested("radius.z");
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

        if($this->getConfig->getNested("set.world") && $this->getConfig->getNested("set.radius") === true){
            if($name->hasPlayedBefore() === false) {
                $w = $this->getConfig->getNested("world");
                $world = $this->getServer()->getLevelByName($w);
                $x = $this->getConfig->getNested("radius.x");
                $x1 = explode(",", $x);
                $z = $this->getConfig->getNested("radius.z");
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
