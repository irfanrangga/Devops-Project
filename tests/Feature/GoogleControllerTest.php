<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;

use Laravel\Socialite\Two\InvalidStateException;
use Mockery;
use Tests\TestCase;

class GoogleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to fake Socialite driver and returned google user.
     */
    private function mockGoogleSocialite(object $googleUser): void
    {
        $socialiteDriver = Mockery::mock();
        $socialiteDriver->shouldReceive('user')
            ->andReturn($googleUser);

        // Also mock redirect() for redirectToGoogle, though tests may ignore.
        $socialiteDriver->shouldReceive('redirect')
            ->andReturn(new RedirectResponse(url('/')));

        $socialiteFacade = Mockery::mock();
        $socialiteFacade->shouldReceive('driver')
            ->with('google')
            ->andReturn($socialiteDriver);

        // Bind into Socialite Facade
        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($socialiteDriver);
    }

    public function test_redirect_to_google_returns_redirect_response(): void
    {
        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('redirect')
            ->once()
            ->andReturn(new RedirectResponse('/'));

        $res = $this->get('/auth/google');
        $res->assertStatus(302);
    }

    public function test_handle_google_callback_creates_user_if_email_not_found(): void
    {
        $googleUser = (object) [
            'getAvatar' => fn () => 'https://example.com/avatar.png',
            'getEmail' => fn () => 'newuser@example.com',
            'getName' => fn () => 'New User',
            'getId' => fn () => 'google-123',
        ];

        // adapt object methods call style
        $googleUser = new class($googleUser) {
            public function __construct(private object $u) {}
            public function getAvatar() { return ($this->u->getAvatar)(); }
            public function getEmail() { return ($this->u->getEmail)(); }
            public function getName() { return ($this->u->getName)(); }
            public function getId() { return ($this->u->getId)(); }
        };

        $this->mockGoogleSocialite($googleUser);

        $res = $this->get('/auth/google/callback');

        $res->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'google_id' => 'google-123',
            'avatar' => 'https://example.com/avatar.png',
            'name' => 'New User',
        ]);

        $this->assertNotNull(session('user_avatar'));
        $this->assertNotNull(session('user_name'));
    }

    public function test_handle_google_callback_updates_google_id_if_existing_user_has_no_google_id(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'google_id' => null,
            'avatar' => null,
            'name' => 'Old Name',
        ]);

        $googleUser = new class {
            public function getAvatar() { return 'https://example.com/avatar2.png'; }
            public function getEmail() { return 'existing@example.com'; }
            public function getName() { return 'Existing User Updated'; }
            public function getId() { return 'google-999'; }
        };

        $this->mockGoogleSocialite($googleUser);

        $res = $this->get('/auth/google/callback');

        $res->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'existing@example.com',
            'google_id' => 'google-999',
            'avatar' => 'https://example.com/avatar2.png',
        ]);
    }

    public function test_handle_google_callback_logs_in_user_and_sets_session_values(): void
    {
        $user = User::factory()->create([
            'email' => 'loginme@example.com',
            'google_id' => 'google-777',
            'avatar' => 'https://example.com/old.png',
            'name' => 'Login Me',
        ]);

        $googleUser = new class {
            public function getAvatar() { return 'https://example.com/new.png'; }
            public function getEmail() { return 'loginme@example.com'; }
            public function getName() { return 'Login Me'; }
            public function getId() { return 'google-777'; }
        };

        $this->mockGoogleSocialite($googleUser);

        $res = $this->get('/auth/google/callback');
        $res->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
        $this->assertEquals('https://example.com/old.png', session('user_avatar'));
        $this->assertEquals('Login Me', session('user_name'));
    }

    public function test_handle_google_callback_when_exception_redirects_to_login_with_errors(): void
    {
        $provider = Mockery::mock();

        $provider->shouldReceive('user')
            ->once()
            ->andThrow(new \Exception('Google Error'));

        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('login'));

        $response->assertSessionHasErrors('email');
    }
}

