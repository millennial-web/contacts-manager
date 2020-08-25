<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\CustomAttribute;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    
    public function showContacts(){
        $contacts = Contact::withCount('custom_attributes')->paginate(10);
        foreach($contacts as $contact){
            $contact = $this::getTeamName($contact);
        }
        return view('contacts', ['contacts'=> $contacts]);
    }

    public function importContacts(){
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
        $validator = Validator::make( $request->all(),[
            'contacts_team_id.*'=> 'required',
            'contacts_phone.*'=> 'required',
            'contacts_email.*' => 'required|email',
        ]);
        if ($validator->fails()) {
            return redirect('import-contacts')
                ->withErrors($validator);
        }

        $new_contacts = [];
        //save regular contact fields (0-3) to the contacts table
        for($i = 0; $i < count($request->contacts_team_id); $i++){
            //check if contact with email already exists
            if($contact = Contact::where('email',$request->contacts_email[$i])->first() ){
                //contact already exists do update
                $contact->team_id = $request->contacts_team_id[$i];
                $contact->name = $request->contacts_name[$i];
                $contact->phone = $request->contacts_phone[$i];
                $contact->save();
            }else{
                $contact = Contact::create([
                    'team_id' => $request->contacts_team_id[$i],
                    'name' => $request->contacts_name[$i],
                    'phone' => $request->contacts_phone[$i],
                    'email' => $request->contacts_email[$i],
                ]);
            }
            //add any custom attributes for each new contact
            $custom_columns = array_slice($request->columns,4);
            foreach($custom_columns as $key){
                $cus_col_name = 'contacts_'.$key;
                if($custom_attribute = CustomAttribute::where('contact_id', $contact->id)->where('key',$key)->first() ){
                    //custom_attribute already exists do update
                    $custom_attribute->value = $request->{$cus_col_name}[$i];
                    $custom_attribute->save();
                }else{
                    //create new custom_attribute for the contact
                    $contact->custom_attributes()->create([
                        'key' => $key, 
                        'value' => $request->{$cus_col_name}[$i]]
                    );
                }
            }
            //refresh the contact object to get the newly created custom attributes
            $contact->custom_attributes;
            array_push($new_contacts, $contact);
        }
        session()->flash('status', 'Your contacts have been successfully imported');
        return redirect('/');
    }


    public function getTeamName($contact){
        //TODO: these will be in a catalog table in production app
        switch($contact->team_id){
            case 1:
                $contact->team_name = 'Alpha';
                break; 
            case 2:
                $contact->team_name = 'Beta';
                break; 
            case 3:
                $contact->team_name = 'Charlie';
                break; 
            case 4:
                $contact->team_name = 'Delta';
                break;
            default: 
                $contact->team_name = '';
        }
        return $contact;
    }


}
