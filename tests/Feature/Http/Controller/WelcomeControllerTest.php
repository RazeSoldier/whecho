<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers \App\Http\Controllers\WelcomeController::report
     */
    public function testSuccess()
    {
        $resp = $this->postJson(route('report'), [
            'system' => ['对舞', '39-DGG'],
            'submitter' => 'admin',
            'signatureName' => 'V928',
        ]);
        $resp->assertStatus(200);
        $resp->assertJson(['status' => 'ok']);
        $this->assertDatabaseHas('drifters_wormhole_reports', [
            'system' => '39-DGG',
            'submitter' => 'admin',
            'notice' => '',
        ]);
    }

    /**
     * @covers \App\Http\Controllers\WelcomeController::report
     */
    public function testFailWithWrongSystem()
    {
        $resp = $this->postJson(route('report'), [
            'system' => ['对舞', '39-DG'],
            'submitter' => 'admin',
            'signatureName' => 'V928',
        ]);
        $resp->assertStatus(422);
        $resp->assertJsonPath('errors.system.0', 'system is invalid.');
    }

    /**
     * @covers \App\Http\Controllers\WelcomeController::report
     */
    public function testFailWithWrongSignatureName()
    {
        $resp = $this->postJson(route('report'), [
            'system' => ['对舞', '39-DGG'],
            'submitter' => 'admin',
            'signatureName' => 'V92',
        ]);
        $resp->assertStatus(422);
        $resp->assertJsonPath('errors.signatureName.0', 'signatureName is invalid.');
    }
}
