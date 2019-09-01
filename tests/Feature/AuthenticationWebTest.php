<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestHelpers;
use TravelCompanion\User;

class AuthenticationWebTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    // REGISTER
    /** @test */
    public function a_client_can_register_with_basic_information()
    {
        $response = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $response->assertRedirect('/auth/email/verify');

        $this->assertDatabaseHas("users", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com"
        ]);
    }

    /** @test */
    public function a_client_cannot_register_without_all_required_fields()
    {
        $responses = [];

        $responses[] = $this->post("/auth/register", [
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "last_name" => "Doe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "last_name" => "Doe",
            "username" => "johndoe",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password_confirmation" => "password",
        ]);

        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
        ]);

        foreach ($responses as $i => $response) {
            $response->assertStatus(302);
            $response->assertSessionHasErrors();
        }

        $this->assertDatabaseMissing("users", [
            "first_name" => "John",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com"
        ]);
    }

    /** @test */
    public function a_client_cannot_register_with_wrong_data()
    {
        $responses = [];

        // First name wrong characters [A-Za-z-']{2,50}
        $responses[] = $this->post("/auth/register", [
            "first_name" => "Johnnythebest!!",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe1",
            "email" => "john1@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // First name too long
        $responses[] = $this->post("/auth/register", [
            "first_name" => "JohnDoeTheBestWithAnExtremelyUnneccesaryLongNameWantsToRegisterForThisApplicationTooPleaseLetMeInIAmSoHypedRightNow",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe2",
            "email" => "john2@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // First name too short
        $responses[] = $this->post("/auth/register", [
            "first_name" => "J",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe3",
            "email" => "john3@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Middle name wrong characters [A-Za-z-'. ]{0,100} (little more complicated regex: points only after word group etc)
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "#R.",
            "last_name" => "Doe",
            "username" => "johndoe4",
            "email" => "john4@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Middle name too long
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "Robert Robert Robert Robert Robert Robert Robert Robert Robert Robert Robert Robert Robert Robert Robert",
            "last_name" => "Doe",
            "username" => "johndoe5",
            "email" => "john5@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // First name wrong characters [A-Za-z-']{2,50}
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe45",
            "username" => "johndoe",
            "email" => "john6@example6.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Last name too short
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "D",
            "username" => "johndoe7",
            "email" => "john7@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Last name too long
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "DoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoeDoe",
            "username" => "johndoe8",
            "email" => "john8@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Username wrong characters [A-Za-z0-9-.]{4,40}
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe9 :)",
            "email" => "john9@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Username too short
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "jd",
            "email" => "john10@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Username too long
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoejohndoejohndoejohndoejohndoejohndoe11",
            "email" => "john11@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Invalid email
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe12",
            "email" => "john12example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Invalid email
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe13",
            "email" => "john13@example@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Email too long (max 80)
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe14",
            "email" => "johnjohnjohnjohnjohnjohn14@examplexamplexamplexamplexamplexamplexample.comcomcomcom",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Wrong location format
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe15",
            "email" => "john15@example.com",
            "home_location" => "-99 5454.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Location wrong characters
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe16",
            "email" => "john16@example.com",
            "home_location" => "(50,8550625;4,3053505)",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        // Password too short .*{}
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe17",
            "email" => "john17@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "pw",
            "password_confirmation" => "pw",
        ]);

        // Password confirmation not matching
        $responses[] = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe18",
            "email" => "john18@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "pass",
        ]);

        foreach ($responses as $i => $response) {
            $response->assertStatus(302);
            $response->assertSessionHasErrors();
        }

        $this->assertDatabaseMissing("users", [
            "first_name" => "John",
            "last_name" => "Doe",
        ]);

        $this->assertDatabaseMissing("users", [
            "username" => "johndoe" . $i,
        ]);
    }

    /** @test */
    public function additional_field_are_ignored_on_registration()
    {
        $response = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
            "birth_date" => "2019-01-01",
            "grandfather_last_name" => "Doeoe",
            "grandmother_favorite_color" => "brown, but not brown brown, rather brown-red-ish",
        ]);

        $response->assertRedirect("/auth/email/verify");
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas("users", [
            "username" => "johndoe",
        ]);

        $this->assertDatabaseMissing("users", [
            "birth_date" => "2019-01-01",
        ]);
    }

    // LOGIN
    /** @test */
    public function a_user_can_log_in()
    {
        $user = factory(User::class)->create();

        $response = $this->post("/auth/login", [
            "email" => $user->email,
            "password" => "password",
        ]);

        $response->assertRedirect("/app");

        $response->assertCookie(config("api.jwt_payload_cookie_name"));
        $response->assertCookie(config("api.jwt_sign_cookie_name"));
    }

    /** @test */
    public function a_user_cannot_access_app_after_registration_without_email_verification()
    {
        $response = $this->post("/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $response->assertRedirect("/auth/email/verify");
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas("users", [
            "username" => "johndoe",
        ]);

        $response = $this->followingRedirects()->post("/auth/login", [
            "email" => "john@example.com",
            "password" => "password",
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertViewIs("auth.verify");
    }

    /** @test */
    public function a_user_cannot_log_in_with_wrong_credentials()
    {
        $user = factory(User::class)->create();

        $response = $this->post("/auth/login", [
            "email" => $user->email,
            "password" => "pwd",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $response = $this->post("/auth/login", [
            "email" => "abc@donttryme.com",
            "password" => "password",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function a_user_cannot_login_whithout_email_and_password()
    {
        $user = factory(User::class)->create();

        $response = $this->post("/auth/login", [
            "password" => "password",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $response = $this->post("/auth/login", [
            "email" => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function additional_fields_are_ignored_on_login()
    {
        $user = factory(User::class)->create();

        $response = $this->post("/auth/login", [
            "email" => $user->email,
            "password" => "password",
            "birth_date" => "2019-01-01",
            "additional_resource" => "Recipe for chocolate chip cookies :)",
       ]);

        $response->assertRedirect("/app");
        $response->assertCookie(config("api.jwt_payload_cookie_name"));
        $response->assertCookie(config("api.jwt_sign_cookie_name"));
    }

    // UNTIL HERE MODIFIED FOR WEB ^

    // FORGOT PASSWORD
    /** @test */
    public function an_authenticated_user_cannot_send_a_password_reset_link()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                            ->followingRedirects()
                            ->post("/auth/password/email", [
                                "email" => $user->email,
                            ]);

        $response->assertViewIs("app");
    }

    /** @test */
    public function an_unauthenticated_user_can_send_a_password_reset_link()
    {
        $user = factory(User::class)->create();

        $response = $this->post("/auth/password/email", [
                        "email" => $user->email,
                    ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');
    }

    /** @test */
    public function an_unexisting_account_cannot_receive_a_password_reset_link()
    {
        $response = $this->post("/api/v1/auth/password/email", [
                        "email" => "hello@inexisting.example.com",
                    ]);

        $response->assertStatus(422);
        $response->assertJSONStructure([
            "success",
            "errors" => [
                "email",
            ],
        ]);
    }

    // RESET PASSWORD

    // RESEND EMAIL VALIDATION
    /** @test */
    public function an_authorized_user_without_validated_email_can_resend_a_verification_email()
    {
        $response = $this->post("/api/v1/auth/register", [
            "first_name" => "John",
            "middle_name" => "R.",
            "last_name" => "Doe",
            "username" => "johndoe",
            "email" => "john@example.com",
            "home_location" => "50.8550625 4.3053505",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $response->assertStatus(201);
        $response->assertJSONStructure([
            "success",
            "data" => [
                "token",
                "token_type",
                "expires_in",
                "user",
            ],
        ]);

        $this->assertDatabaseHas("users", [
            "username" => "johndoe",
        ]);

        $response = $this->actingAs(User::where('username', 'johndoe')->first())
                            ->post("/api/v1/auth/email/resend");

        $response->assertStatus(200);
        $response->assertJSONStructure([
            "success",
            "message",
        ]);
    }

    /** @test */
    public function an_authorized_user_with_validated_email_cannot_resend_a_verification_email_but_receives_a_confirmation_af_validation()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                            ->post("/api/v1/auth/email/resend");

        $response->assertStatus(200);
        $response->assertJSONStructure([
            "success",
            "message",
        ]);
    }

    /** @test */
    public function an_unauthorized_user_cannot_resend_a_verification_email()
    {
        $response = $this->post("/api/v1/auth/email/resend");

        $response->assertStatus(401);
        $response->assertJSONStructure([
            "success",
            "message",
        ]);
    }

    // 404, actually does not belong in this file
    /** @test */
    public function any_request_to_inexisting_page_returns_a_404()
    {
        $response = $this->post("/404");

        $response->assertStatus(404);
    }
}
