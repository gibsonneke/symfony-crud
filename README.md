# Symfony-CRUD
Required: Git, Composer, PHP

1. Clone the code from GitHub:

    # git clone https://github.com/gibsonneke/symfony-crud.git

2. Run Composer:

    # composer install

3. Set up the database

    # php bin/console doctrine:database:create
	# php bin/console doctrine:schema:update --force
	
4. Run the built-in web server

	# php bin/console server:run
	
5. Run the application in your favourite browser

	# http://127.0.0.1:8000