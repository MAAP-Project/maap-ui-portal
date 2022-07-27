# MAAP Portal

The joint ESA-NASA Multi-Mission Algorithm and Analysis Platform (MAAP) focuses on developing a collaborative data system enabling collocated data, processing, and analysis tools for NASA and ESA datasets. To facilitate discovery, user engagement, and support for the platform, we maintain a portal where users can learn about the platform by way of news articles and user guides. The portal also provides centralized access to MAAP's tools and services.

##Technical Information

### Local Development

#### Requirements

At the moment, the only requirement for local development is to have [Docker](https://www.docker.com/) installed as all local development is performed using containers.

#### Getting Started

  1. Clone the Repository
  2. Create files for each _docker secret_ that is referenced in the `docker-compose.yml` file and store a password of your choice in each respective file. ***These files should never be saved to the repository.***

#### Sample Development workflow
  1. Make some changes to the code...
  2. Build the images, `make bulid-all`
  3. Start the services, `make start`.
  4. View the running service, navigate to 'http://localhost:8080/' or run `make open` in a terminal window.

Changes made to content that resides in folders that have a mapped volume may not require rebuilding the images and restarting the containers.

Note: There are additional receipes in the `Makefile` that may help during development.
