<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class CotactsManagerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * tests that home page shows the "You have no contacts" message.
     *
     * @return void
     */
    /** @test */
    public function home_contact_list_has_no_contacts()
    {
        //disable laravel error handeling to see raw phpunit error results
        $this->withoutExceptionHandling();
        
        $response = $this->get('/');
        // $html = $response->dump();
        $response->assertSeeText("You have no contacts");
        $response->assertStatus(200);
    }

    /**
     * tests upload of csv and display of process-contacts view with data.
     *
     * @return void
     */
    /** @test */
    public function import_csv_shows_process_contacts_with_correct_data()
    {
        //disable laravel error handeling to see raw phpunit error results
        $this->withoutExceptionHandling();
        $stub = __DIR__.'/stubs/testcontactsimport.csv';
        $name = str_random(8).'.csv';
        $path = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file = new UploadedFile($path, $name, filesize($path), null, null, true);
        $response = $this->call('POST', '/process-contacts', [], [], ['file' => $file], []);
        $response->assertStatus(200);
        $response->assertSee('desarrollowebuno@gmail.com');
        $response->assertSee('jomars1048@gmail.com');
        $response->assertSee('teachgsmith@hotmail.com');
        $response->assertSee('jessica@yahoo.com');
        $response->assertSee('bradthepitt@outlook.com');
    }

    /**
     * tests that the data from process-contacts form was saved correctly to the database
     *
     * @return void
     */
    /** @test */
    public function process_contacts_form_data_is_saved_to_database()
    {
        //disable laravel error handeling to see raw phpunit error results
        $this->withoutExceptionHandling();
        $payload = [
            "columns" => [
                "team_id",
                "name",
                "phone",
                "email",
                "favorite_color",
                "country",
                "gender",
            ],
            "contacts_team_id" => [
                "1",
                "1",
                "2",
                "3",
                "3",
            ],
            "contacts_name" => [
                "Mike Soto",
                "Joana Bisset",
                "George Smith",
                "Jessica Simpson",
                "Brad Pitt",
            ],
            "contacts_phone" => [
                "5524435444",
                "4425545656",
                "9948837777",
                "1234567898",
                "92899284444",
            ],
            "contacts_email" => [
                "desarrollowebuno@gmail.com",
                "jomars1048@gmail.com",
                "teachgsmith@hotmail.com",
                "jessica@yahoo.com",
                "bradthepitt@outlook.com",
            ],
            "contacts_favorite_color" => [
                "blue",
                "red",
                null,
                "pink",
                "yellow",
            ],
            "contacts_country" => [
                "Mexico",
                "France",
                "USA",
                "USA",
                "USA",
            ],
            "contacts_gender" => [
                "Male",
                "Female",
                "Male",
                "Female",
                null,
            ]
        ];
        $response = $this->call('POST', '/save-contacts', $payload);
        //should redirect to home
        $response->assertRedirect('/');
        //check database for inserted record
        $this->assertDatabaseHas('contacts', [
            'email' => 'desarrollowebuno@gmail.com'
        ]);
    }
}
