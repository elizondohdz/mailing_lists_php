<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = Contact::all();
        $groups = Group::all();

        foreach ($groups as $group) {
            for ($i=0; $i < rand(1, 10); $i++) {
                $contact = $contacts->random();
                ContactGroup::factory()->create([
                    'contact_id' => $contact->id,
                    'group_id' => $group->id
                ]);
            }
        }
    }
}
