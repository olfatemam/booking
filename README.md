# booking
booking demo system

clone https://github.com/olfatemam/booking.git

create mysql and adjust the .env with db name and credentials

from project folder:
1. run composer update
2. run php artisan migrate 
3. run php artisan db:seed

4. run ./vendor/bin/phpunit

finished:
creation of booking test cases
booking successfully update test case
availability list test case


TBD:
test cases for booking failed update
test cases for models: cleaner, customer 
code documentation