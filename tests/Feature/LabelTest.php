<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
    }
    /**
     * A basic feature test example.
     */
    public function test_store_label(): void
    {
        //preperation

        //action
        $this->postJson(route('label.store'), ['title' => 'my label', 'color' => 'blue'])->assertCreated();

        //assert
        $this->assertDatabaseHas('labels', ['title' => 'my label', 'color' => 'blue']);
    }

    public function test_delete_label() {
        // preperation
        $label = $this->createLabel();

        // action
        $this->deleteJson(route('label.destroy', $label->id))->assertNoContent();

        // assert
        $this->assertDatabaseMissing('labels', ['title' => $label->title]);
    }

    public function test_update_label() {
        // preperation
        $label = $this->createLabel();

        // action
        $this->patchJson(route('label.update', $label->id), [
            'color' => 'color new',
            'title' => $label->title,
        ])->assertOk();

        // assert
        $this->assertDatabaseHas('labels', ['title' => $label->title]);
    }

    public function test_fetch_all_label_by_user() {
        // preperation
        $this->createLabel();
        $label = $this->createLabel(['user_id' => auth()->user()->id]);
        
        // action
        $respon = $this->getJson(route('label.index'))->assertOk()->json('data');
        // dd ($respon);
        // assert
        $this->assertEquals($respon[0]['title'], $label->title);
    }
}
