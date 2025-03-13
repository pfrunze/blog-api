## Installation

Follow these steps to set up the project:

1. **Install dependencies**
   - Use composer to install dependencies:
     ```bash
     composer i
     ```

2. **Set Up Environment Configuration**
   - Create a `.env` file by copying the example file:
     ```bash
     cp .env.example .env
     ```

3. **Run Database Migrations**
   - Execute the migrations to set up the database schema:
     ```bash
     php artisan migrate
     ```

4. **Seed the Database**
   - Populate the database with initial data:
     ```bash
     php artisan db:seed
     ```

5. **Run Migrations for Testing Database**
   - Set up the testing database
     ```bash
     php artisan migrate --database=testing_mysql
     ```

6. **Generate Passport Encryption Keys**
   - Create the encryption keys for Laravel Passport:
     ```bash
     php artisan passport:keys
     ```

7. **Run Tests**
   - Verify the application is working as expected by running tests:
     ```bash
     php artisan test
     ```

8. **Start the Development Server**
   - Launch the local server (default: `http://localhost:8000`):
     ```bash
     php artisan serve
     ```

## API Authentication Setup

To access protected routes, you need to generate a personal access token using Laravel Passport.

1. **Create a Personal Access Client**
   - Generate a personal access client for OAuth:
     ```bash
     php artisan passport:client --personal
     ```

2. **Generate an Access Token**
   - Use Laravel Tinker to create a token for a specific user:
     ```bash
     php artisan tinker
     ```
   - Inside Tinker, run:
     ```php
     echo App\Models\User::where('email', 'alice@example.com')->first()->createToken('Postman Token')->accessToken;
     ```
   - Copy the generated token output.

3. **Test Protected Routes in Postman**
   - Open Postman.
   - Set the request type to `GET`, `POST`, etc., depending on the route.
   - Add the generated token as a **Bearer Token** in the Authorization tab:
     ```
     Authorization: Bearer <paste-your-token-here>
     ```
   - Send the request to a protected route (e.g., `http://localhost:8000/api/some-protected-route`).