<?php

namespace Vandar\Cashier\Tests\Compatibility;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;
use Vandar\Cashier\Client\Authenticate;
use Vandar\Cashier\Client\CustomResponse;
use Vandar\Cashier\Events\MandateCreating;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Tests\Fixtures\User;
use Vandar\Cashier\VandarCashierServiceProvider;

class FrameworkCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [VandarCashierServiceProvider::class];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Fixtures/migrations');
    }

    public function test_migrations_relationships_scopes_and_events(): void
    {
        Event::fake([MandateCreating::class]);
        $user = User::create(['name' => 'Test', 'email' => 'test@example.com', 'password' => 'unused']);
        $mandate = $user->mandates()->create([
            'token' => 'test-token', 'count' => 2, 'limit' => 10000,
            'expiration_date' => now()->addDay()->toDateString(),
            'bank_code' => 'test', 'is_active' => true,
        ]);

        $this->assertTrue($user->mandates()->active()->first()->is($mandate));
        Event::assertDispatched(MandateCreating::class);
        $this->assertDatabaseHas('vandar_mandates', ['user_id' => $user->id]);
        $this->assertTrue($mandate->refresh()->revoke());
        $this->assertFalse((bool) $mandate->fresh()->is_active);
    }

    public function test_response_implements_psr7_and_decodes_json(): void
    {
        $response = new CustomResponse(new Response(200, ['X-Test' => 'ok'], '{"status":1}'));

        $this->assertSame(['status' => 1], $response->json());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame('2', $response->withProtocolVersion('2')->getProtocolVersion());
        $this->assertSame(['X-Test' => ['ok']], $response->getHeaders());
        $this->assertTrue($response->hasHeader('X-Test'));
        $this->assertSame(['ok'], $response->getHeader('X-Test'));
        $this->assertSame('ok', $response->getHeaderLine('X-Test'));
        $this->assertSame(201, $response->withStatus(201)->getStatusCode());
        $this->assertSame('OK', $response->getReasonPhrase());
        $this->assertSame('new', $response->withHeader('X-Test', 'new')->getHeaderLine('X-Test'));
        $this->assertSame(['ok', 'new'], $response->withAddedHeader('X-Test', 'new')->getHeader('X-Test'));
        $this->assertFalse($response->withoutHeader('X-Test')->hasHeader('X-Test'));
        $this->assertSame('body', (string) $response->withBody(Utils::streamFor('body'))->getBody());
    }

    public function test_missing_invalid_and_valid_token_files(): void
    {
        Storage::fake('local');
        $auth = new class extends Authenticate {
            public static function cachedData(): array
            {
                return parent::getData();
            }
        };

        $this->assertSame([], $auth::cachedData());
        Storage::disk('local')->put('vandar_auth.json', 'invalid json');
        $this->assertSame([], $auth::cachedData());
        Storage::disk('local')->put('vandar_auth.json', '{"access_token":"test"}');
        $this->assertSame(['access_token' => 'test'], $auth::cachedData());
    }

    public function test_webhook_routes_are_registered(): void
    {
        $this->assertSame('/vandar/webhook/withdrawal', route('vandar.webhook.withdrawal', [], false));
        $this->assertSame('/vandar/webhook/settlement', route('vandar.webhook.settlement', [], false));
    }
}
