# OverseerV2

The second iteration of the Overseer games.

The database dump can be found at `database.sql`. Here, it's the master branch that contains the last official live version.

There's a `.env.dist` you can fill out a `.env` from.

Remember to run `composer install`.

The repository also contains the status of the then upcoming Overseer v2.5, on the dev branch and a couple of feature branches.

Godspeed.

## Setup video

https://youtu.be/sNQw6eO1aJ0

## Dockerized Setup

1. Install [Docker](https://www.docker.com/) (for dev work, you can also look at installing [Docker Desktop](https://www.docker.com/products/docker-desktop/) to have the containers in an easy-to-view GUI)
2. Copy `.env.dist` to `.env`, and fill up the credentials appropriately
3. Run the following command in the base of this repository: `docker compose --profile dev up -d --build`
4. Wait for the build to finish. This may take a while depending on how fast your internet connection and computer are. An initial build is expected to take about 5 minutes.
5. The website should now be accessible in `http://localhost:8000`, with the PHP side directly accessible in `http://localhost:9000`, the Rust side directly accessible in `http://localhost:8010`, and the MySQL database directly accessible via `localhost:3306`.

> If you want to run the website without building again in the future, do `docker compose --profile dev up -d` instead.

> WARNING: Rebuilds might fail if the Rust container is running. This is due to the container still holding the lock for the Rust project, making the Docker build agent unable to build the project. If this is happening to you, turn the Rust container off and try again.
