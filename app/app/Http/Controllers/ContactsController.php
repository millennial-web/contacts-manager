<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\CustomAttribute;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    public function importContacts()
    {
        return view('import');
    }

    public function processContacts()
    {
        //Place validation rules, only a CSV file will be accepted. 
        // If there is an error the user will be redirected back to the import page.
        request()->validate([
            'file' => 'required|mimes:csv,txt'
        ]);
        
        //get file from upload
        $path = request()->file('file')->getRealPath();
        //open the file path
        $file = file($path);
        //parse the file into a data array
        $csv = array_map('str_getcsv', $file);
        //get column names from first line
        $columns = $csv[0];
        //everything else will be the contact rows
        $contacts = array_slice($csv, 1);
        //return the view for processing contacts from the csv
        return view('process_contacts', ["columns" => $columns, "contacts" => $contacts]);
    }

    public function saveContacts(Request $request)
    {       
        // dd($request->all());
        //         array:8 [▼
        //   "_token" => "J9UXugr9occQdcecgj0efRznRD0vQJNK08heezcA"
        //   "columns" => array:6 [▼
        //     0 => "name"
        //     1 => "phone"
        //     2 => "email"
        //     3 => "favorite_color"
        //     4 => "country"
        //     5 => "gender"
        //   ]
        //   "contacts_name" => array:5 [▼
        //     0 => "Mike Soto"
        //     1 => "Joana Bisset"
        //     2 => "George Smith"
        //     3 => "Jessica Simpson"
        //     4 => "Brad Pitt"
        //   ]
        //   "contacts_phone" => array:5 [▼
        //     0 => "5524435444"
        //     1 => "4425545656"
        //     2 => "9948837777"
        //     3 => "1234567898"
        //     4 => "92899284444"
        //   ]
        //   "contacts_email" => array:5 [▼
        //     0 => "desarrollowebuno@gmail.com"
        //     1 => "jomars1048@gmail.com"
        //     2 => "teachgsmith@hotmail.com"
        //     3 => "jessica@yahoo.com"
        //     4 => "bradthepitt@outlook.com"
        //   ]
        //   "contacts_favorite_color" => array:5 [▼
        //     0 => "blue"
        //     1 => "red"
        //     2 => null
        //     3 => "pink"
        //     4 => "yellow"
        //   ]
        //   "contacts_country" => array:5 [▼
        //     0 => "Mexico"
        //     1 => "France"
        //     2 => "USA"
        //     3 => "USA"
        //     4 => "USA"
        //   ]
        //   "contacts_gender" => array:5 [▼
        //     0 => "Male"
        //     1 => "Female"
        //     2 => "Male"
        //     3 => "Female"
        //     4 => null
        //   ]
        // ]
        $validator = Validator::make( $request->all(),[
            'contacts_team_id.*'=> 'required',
            'contacts_phone.*'=> 'required',
            'contacts_email.*' => 'sometimes|email',
        ]);

        if ($validator->fails()) {
            return redirect('import-contacts')
                        ->withErrors($validator);
        }



        //save regular contact fields to the contacts table
        // $contact = 


        session()->flash('status', 'Your contacts have been successfully imported');
        return redirect('import-contacts');
    }



}
