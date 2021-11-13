<?php

namespace MarcoRieser\ErrorMessages;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [Tags\ErrorMessages::class];
}
