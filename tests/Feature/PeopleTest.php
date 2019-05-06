<?php

namespace Tests\Feature;

use App\People;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PeopleTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_post_json_to_people()
    {
        $this->withoutExceptionHandling();

        $people = '{"data":[{"first_name":"cody","last_name":"duder","age":38,"email":"codyduder@causelabs.com","secret":"VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="},
        {"first_name":"ladee","last_name":"linter","age":99,"email":"lindaladee@causelabs.com","secret":"cmVzb3VyY2UgdmlvbGF0aW9u"}]}';
    
        $response = $this->json('POST', '/api/people', (array) json_decode($people));

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

         $res = json_decode($people)->data;

        $this->get('/api/people')->assertSee(json_decode($people)->data[0]->first_name);
    }

    /** @test */
    public function a_user_can_delete_a_record()
    {
        $people = '{"data":[{"first_name":"cody","last_name":"duder","age":38,"email":"codyduder@causelabs.com","secret":"VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="},
        {"first_name":"ladee","last_name":"linter","age":99,"email":"lindaladee@causelabs.com","secret":"cmVzb3VyY2UgdmlvbGF0aW9u"}]}';
    
        $response = $this->json('POST', '/api/people', (array) json_decode($people));
        // add second record
        $people2 = '{"data":[{"first_name":"jand","last_name":"doe","age":25,"email":"janedoe@test.com","secret":"abc123"},
        {"first_name":"john","last_name":"doe","age":49,"email":"johndoe@test.com","secret":"def456"}]}';
    
        $response = $this->json('POST', '/api/people', (array) json_decode($people2))->decodeResponseJson();
        $recordCount = People::all()->count();

        $this->assertEquals(2, $recordCount);
        $response = $this->delete("/api/people/{$response['last_insert_id']}");

        // Verify record was deleted
        $recordCount = People::all()->count();
        $this->assertEquals(1, $recordCount);
    }
}
