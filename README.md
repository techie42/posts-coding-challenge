# Coding Challenge - Posts

This is a response to a coding challenge using Laravel Sail and other components, including VueJS, Inertia, etc...

## Assumptions:

- You do not need to be logged-in to create a post
- Only logged-in users can "like" a post

> [CAUTION]
> This is **NOT** a production-ready project, and so should **NOT** be deployed outside of your local machine!
> i.e. only use this project locally.

## Pre-requisites for local development / testing:

- Sufficient disk space for Docker images, containers, volumes, as well as the code
  - approx. 3.5GB
- Docker installed and running
  - https://www.docker.com/products/docker-desktop/
- Composer installed
  - https://getcomposer.org/download/
- A command line tool
- An editor or IDE
- Anything else you can think of ...

## Usage

- Clone this repository to your local machine
- Copy `.env.example` to `.env` in the main project directory
  - Note: this already contains private / secure details, but as this is just a local demo project, it's not an issue
- Run `composer install`
  - Note: you might be asked for a GitHub OAuth token - follow any instructions displayed in the console
- Run `php artisan sail:install`
  - Choose `mysql` and press Enter
  - The install should run - follow the prompts
- Run `./vendor/bin/sail up`
- Run `./vendor/bin/sail artisan migrate`
- Whilst leaving that process running, open another command line terminal in the same project directory
- In this new terminal:
  - Run `./vendor/bin/sail composer install`
  - Run `npm run dev`
- Assuming all the above has worked smoothly, you should now be able to open your web-browser:
  - `http://localhost`
- You should see the project running in the browser
- Enter some values for a new post, then save it - have fun!
- 
