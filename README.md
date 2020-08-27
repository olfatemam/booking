# booking
booking demo system

clone https://github.com/olfatemam/booking.git

create mysql and adjust the .env with db name and credentials

from project folder:

1. run php artisan migrate 
2. run php artisan db:seed

using postman sent the post api: http://localhost/booking/public/api/bookings with the following parameters in the body:
cleaner_id: chose from 1 to 5 to use one of the seeded values
customer_id: chose from 1 to 5 to use one of the seeded values
start: datetime in the format 'Y-m-d H:i:s'
duration: 2 or 4


so far I only testing the creation of the first booking, and I need extra time for properly test the sql query for the booking operation.

TBD:
test booking all cases 
test updating all cases
test cases: cleaner, customer 