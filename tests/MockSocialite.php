<?php

namespace Tests;

use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\User;

trait MockSocialite
{
    /**
     * Mock the Socialite Factory, so we can hijack the OAuth Request.
     * @param \App\User $user
     * @param string $token
     */
    public function mockSocialiteFacade(\App\User $user, string $token = 'foo')
    {
        $socialiteUser = $this->createMock(User::class);
        $socialiteUser->token = $token;
        $socialiteUser->id = $user->id;
        $socialiteUser->name = $user->name;
        $socialiteUser->email = $user->email;
        // the socialite provider class can be from any vendor
        $provider = $this->createMock(FacebookProvider::class);
        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        $stub = $this->createMock(Socialite::class);
        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with our mock
        $this->app->instance(Socialite::class, $stub);
    }
}
