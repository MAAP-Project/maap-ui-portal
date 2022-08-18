# MAAP Portal

The joint ESA-NASA Multi-Mission Algorithm and Analysis Platform (MAAP) focuses on developing a collaborative data system enabling collocated data, processing, and analysis tools for NASA and ESA datasets. To facilitate discovery, user engagement, and support for the platform, we maintain a portal where users can learn about the platform by way of news articles and user guides. The portal also provides centralized access to MAAP's tools and services.

## Technical Information

### Local Development

#### Requirements

At the moment, the only requirement for local development is to have [Docker](https://www.docker.com/) installed as all local development is performed using containers.

#### Getting Started

  1. Clone the Repository
  2. Create files for each _docker secret_ that is referenced in the `docker-compose.yml` file and add the information (of your choice) in each respective file. ***These files should never be saved to the repository.***
  3. Get a database dump from the portal database of any environment (DIT, UAT, OPS) and save the file to `db/init` so that when the service is run, it initializes the database with this data. ***These files should never be saved to the repository.***
  4. Run `make build` to create the Docker images
  5. Run `make start` to start the Docker containers
  6. Run `make open` to view the website in a browser or navigate to [http://localhost:8080/](http://localhost:8080/).  If you need to login to the Admin dashboard run `make open-login` or go to [http://localhost:8080/wp-login.php?external=wordpress](http://localhost:8080/wp-login.php?external=wordpress) to login. ***You must have a password set in the environment from which the database dump was retrieved because local login is not integrated with URS.*** 

#### Sample Development workflow
  1. Make some changes to the code...
  2. Build the images, `make bulid-all`
  3. Start the services, `make start`.
  4. View the running service, navigate to [http://localhost:8080/](http://localhost:8080/) or run `make open` in a terminal window.

Changes made to content that resides in folders that have a mapped volume may not require rebuilding the images and restarting the containers.

Note: There are additional receipes in the `Makefile` that may help during development.
