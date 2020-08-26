# contacts-manager
A Contacts Manager with auto Import from CSV / Excel Files

# Running Demo
* clone repository
* run composer install for dependancies
* create mysql database contacts_app
* create user contacts_app with password 'secret' 
* update local .env file with database and user/pass
* run php migrate:fresh to drop any existing tables and recreate contacts and custom_attributes tables
* run php artisan serve
* visit http://localhost:8000/
* download csv template example with preloaded contacts 
* click import button to load the downloaded file
* verify data from file and make any desired changes
* click looks good to save the new contacts to the database
* you should be redirected back to home with updated contacts listing
* any existing emails will be updated with new parameters to avoid duplicate contacts

# Running Feature/Unit Tests
* in command line, cd into project root directory
* run ./vendor/bin/phpunit tests/Feature/ContactsManagerTest
* the automated tests will run the same workflow as above using assertions. 
* All should pass. 
