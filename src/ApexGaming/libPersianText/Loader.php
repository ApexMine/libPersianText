<?php

declare(strict_types=1);

namespace ApexGaming\libPersianText;

use pocketmine\plugin\PluginBase;

/**
 * Lets libPersianText be installed once as a normal plugin. It does nothing
 * by itself: PocketMine shares every loaded plugin's classes, so any plugin
 * with `depend: [libPersianText]` can use Persian::fix() directly.
 */
final class Loader extends PluginBase{
}
