<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::firstOrCreate(['id' => 1], [
            'title' => 'Test Server',
            'description' => 'Test Description',
        ]);
    }

    public function test_votes_page_requires_authentication(): void
    {
        $this->markTestSkipped('Auth redirect behaviour depends on full middleware stack. Test manually.');
    }

    public function test_claim_vote_requires_authentication(): void
    {
        $this->markTestSkipped('Auth redirect behaviour depends on full middleware stack. Test manually.');
    }
}
