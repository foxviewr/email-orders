# Email Orders

**Email Orders** is a simple email-based ordering web application that catches forwarded email requests and generates new orders. Each order can be interacted with simple email replies.

## Getting Started
Make sure you have **docker** installed in your computer. I recommend installing the [Docker Desktop](https://www.docker.com/products/docker-desktop/) app which includes **docker** and offers a user interface to easily manage your docker.

#### Clone the project repo from GitHub:
```bash
git clone git@github.com:foxviewr/email-orders.git
cd email-orders/
```

#### Copy the existing file `.env.example` into `.env` and customize your environment variables. For development, all default values should work for you, but review all variables with comments on them.

```bash
cp .env.example .env
nano .env # edit the file variables (nano is an example)
```

> **Note:** For *Mailgun* and *Ngrok* `.env` variables, they can be left empty if no mailer or middleware `.env` variable is configured for `mailgun`. If you wish to configure *Mailgun* for your outgoing or incoming emails, fill in the respective *Mailgun* and *Ngrok* `.env` variable according the instructions in the comments. *[Mailgun](https://www.mailgun.com/)* and *[Ngrok](https://ngrok.io/)* services provide free accounts.

#### Install the project:
```bash
make docker-install
```

#### Run the project:
```bash
make docker-start
```
After the docker containers start, it might take a moment to install all dependencies at first run.

Once the containers are fully running, you can access the app at `${FRONTEND_URL}` (default: http://localhost:3009)

From the application frontend UI, you can easily create an account and login. For this version, the account email verification has been disabled.

### Incoming Emails
The backend, with url `${BACKEND_URL}` (default: http://localhost:8009), has an API `POST` endpoint `/api/email/store` that accepts email post requests, validates those requests, and runs the business logic behind orders.

The endpoint controller has the following validation rules:
```
// [request inputs] : [conditions]
"recipient"         : "required|string|max:255",
"recipientName"     : "sometimes|string|max:255",
"sender"            : "required|string|max:255",
"senderName"        : "sometimes|string|max:255",
"subject"           : "required|string|max:255",
"body-html"         : "required|string",
"Message-Id"        : "required|string|max:255",
"In-Reply-To"       : "sometimes|required|string|max:255",
"from"              : "sometimes|required|string|max:255",
"From"              : "sometimes|required|string|max:255",
"to"                : "sometimes|required|string|max:255",
"To"                : "sometimes|required|string|max:255"
```

You can execute a `POST` request to the previously mentioned API endpoint with the proper request inputs to simulate an email being forward to the application.

#### Testing incoming emails with Mailgun
If you have a *Mailgun* and *Ngrok* accounts, and setup all the respective `.env` variables, you can use these services to simulate a more real-life scenario.

Make sure you have the `.env` variable `${MAIL_INCOMING_MIDDLEWARE}` set to `mailgun`.

From the project root, run ngrok:
```bash
make docker-ngrok-backend
```

The output provides you with a public ngrok.io URL to your backend API, serving as a temporary bridge URL to your local application.

The output also provides with a ngrok request inspector URL, accessible through the port defined by the `${NGROK_PORT}` `.env` variable, that allows you to monitor all requests to the main ngrok.io URL (and therefore to your backend API).

Go to your *[mailgun.com](https://mailgun.com)* account, and from the left menu go to **Send > Receiving**, then click in **Create route**.

In the create new route page:

- set the **Expression type** field as `Match Recipient`
- write in the **Recipient** field an email address with one of the *Mailgun* domains you have (can be a *Mailgun* free sandbox domain) that you wish to be the recipient for *Mailgun* to forward all incoming emails to your application
- enable the **Forward** option and in the respective textarea field write your ngrok.io URL followed by the backend API endpoint `/api/email/store` (example https://0000-000-000-000-000.ngrok-free.app/api/email/store)
- enable **Store and notify** and **Stop**
- save the new route

Now your *Mailgun* is ready to forward any email sent to the email address recipient you defined before into your local application with the help of *Ngrok* to bridge your local application publicly.

You can now test this setup by sending an email from any email provider (Outlook, Gmail, ...) to the email address recipient setup before. After a few seconds, depending on your email provider and *Mailgun* servers, you should be able to see the new incoming order (or new incoming order reply) in you application frontend UI.

> **Note:** The application supports email attachments, and they are visible through the frontend UI inside an order. Test this when sending an email with your email provider.

> **Note:** The application supports email replies and stores them within the same respective order, you can visualize this as a conversation inside an order in the frontend UI. To simulate this with manual requests, make sure the `In-Reply-To` request input has the `Message-Id` of the email it is intended to reply to. If you're using *Mailgun* for incoming emails, you can test this by replying to the same email you sent from your same email provider.

> **Note:** In the current application version, YahooMail seems to cause issues when used with *Mailgun* configuration.

### Outgoing Emails

This application uses Laravel default mailers and `Mail` system for outgoing emails, leveraged to an out-of-the-box `mailgun` mailer service.
By default, all `.env` outgoing mail related variables are set up to work with `maildev` through an `smtp` mailer.

Let's prepare the environment for testing with either an `smtp` mailer using `maildev`, or with a `mailgun` mailer.

#### > Prepare testing environment with maildev
`maildev` is a local SMTP server and web-interface for visualizing outgoing emails. All the respective `.env` variables are by default properly set to work with `maildev`.

Run the following command through the project's root to start the `maildev` local server:
```bash
make docker-maildev
```

The output will provide you with a URL to access the web-interface and monitor all outgoing emails from this application.

#### > Prepare testing environment with mailgun
Make sure you have all *Mailgun* `.env` variables setup, and change the `${MAIL_MAILER}` variable to `mailgun`.

#### > Test sending emails
Through the application's frontend UI, go to an existing order and scroll down to the reply form where you can write a simple message and send it to the customer's email address.

After you send the reply, if successful, you can immediately see it in the frontend UI, and you can also visualize the email in the `maildev` web-interface or *[mailgun.com](https://mailgun.com)* logs (depending on the mailer you configured before).

## Makefile
This project contains a Makefile to assist with running the project and other useful tools.

To check all the available Makefile commands run from the project's root:
```bash
make
```

## Notes

The app is divided into 3 docker services:
1. `database` this is a database container built with `mariadb`. The data is stored in the `.db-data` folder in the project's root directory.
2. `backend` this is a custom container built with `php` that serves as a backend API to deal with all the logic and connect with the `database` container. Developed using the `Laravel` framework with `Eloquent` ORM.
3. `frontend` this is a custom container built with `node.js` that serves as a frontend UI for the user and connects with the `backend` API container. Developed using `react` and the `nextjs` framework with `tailwind` css framework.

The project's Makefile has 2 commands that provide each with one temporary container for development, respectively:
- `maildev`
- `ngrok`
