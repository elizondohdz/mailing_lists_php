<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{

    public function testStoreApiContact()
    {
        $contactData = [
            'fullname' => 'Fake Name',
            'email' => fake()->email,
            'phone' => '444-22222'
        ];

        $response = $this->post('/api/contacts', $contactData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('contacts',[
            'fullname' => $contactData['fullname']
        ]);
    }

    public function testGetApiContacts()
    {
        $response = $this->get('/api/contacts');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'email',
                    'phone',
                    'uuid'
                ]
            ]
        ]);
    }

    public function testPutApiContacts()
    {
        $existentContact = Contact::first();

        $contactData = [
            'fullname' => $existentContact->fullname . ' Edition',
            'email' => $existentContact->email,
            'phone' => $existentContact->phone
        ];

        $response = $this->put('/api/contacts/' . $existentContact->uuid, $contactData);

        $response->assertStatus(200);

        $updatedContact = Contact::find($existentContact->id);

        $this->assertNotEquals($updatedContact->fullname, $existentContact->fullname);
    }

    public function testDeleteApiContacts()
    {
        $existentContact = Contact::first();

        $response = $this->delete('/api/contacts/' . $existentContact->uuid);

        $response->assertStatus(204);
    }


}
