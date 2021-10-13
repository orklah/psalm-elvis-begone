<?php declare(strict_types=1);
namespace Orklah\ElvisBegone;

use Orklah\ElvisBegone\Hooks\ElvisBegoneHooks;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        if(class_exists(ElvisBegoneHooks::class)){
            $registration->registerHooksFromClass(ElvisBegoneHooks::class);
        }
    }
}
