# Authentication & Authorization in Laravel

## 🛠️ Step 1: Create Middleware Folder

To create the Middleware folder, run the following command:

```bash
php artisan make:middleware AuthMiddleware
```

This command will generate a new middleware class called `AuthMiddleware` in the `app/Http/Middleware` directory. The middleware will handle checking for a valid JWT token in the cookies when a user makes a request.

🔑 **Usage**: To use this middleware, simply wrap the endpoint that you want to secure with it inside `api.php`.

---

## 📧 Step 2: Create Mail Folder

To create the Mail folder, run the following command:

```bash
php artisan make:mail VerificationMail
```

This command will generate a new mail class called `VerificationMail` in the `app/Mail` directory. This mail class will be used to send verification emails to users during registration.

---

## ✉️ Mail Configuration

To make sure the mail functionality works correctly, you need to configure the email settings in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Ensure that your mail settings are correct according to the email service you are using. Laravel supports services like **Mailgun**, **SES**, **SMTP**, and others.

---

## 📝 Step 3: Create a Register Function inside AuthController

Here's the flow of the `register` function:

1. **Validate** email and password.
2. **Check** if the user exists in the database.
3. If not, **hash** the password and create a new user with `is_verified` set to `false`.
4. Get the name from the email and set it as the `name`.
5. **Generate** a 6-digit verification code.
6. Set an expiration time for the verification code.
7. **Save** the verification code in the database, along with the email and expiration time.
8. **Send** a verification email with the newly created verification code.

---

## ✅ Step 4: Create a `verifyCode` Function inside VerificationController

Here's the flow of the `verifyCode` function:

1. **Validate** email and code.
2. **Find** the verification code in the database.
3. **Check** if the code is expired.
4. **Find** the user associated with the code.
5. If the user is found, **set** `is_verified` to `true`.
6. **Delete** the verification code from the database.
7. **Generate** a JWT token.
8. **Send** the token inside cookies.

---

## 🔑 Step 5: Create a Login Function inside AuthController

Here's the flow of the `login` function:

1. **Validate** email and password.
2. **Find** the user and perform necessary validation.
3. **Generate** a JWT token.
4. **Send** the token inside cookies.

---

With these steps, your Laravel application will have a full authentication and authorization system using JWT and email verification.
