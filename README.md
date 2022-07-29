# StreamStats

This application is aimed at helping Twitch viewers get a quick look at how the channels they watch compare to the top 1000 live streams. User will log into the application with a Twitch account via an OAuth

## Getting Started

This API is built and runs on Laravel PHP and MySQL RDBMS and Vue3 with Vite. 

### Prerequisites
* MySQL DBMS ()
* PHP >= 8.0
* BCMath PHP Extension
* Ctype PHP Extension
* JSON PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* Node
* NPM
* Vite

### Must have
* [Composer](https://getcomposer.org/doc/00-intro.md) - PHP Package Manager
* [Node](https://nodejs.org/en) - Node comes with Node Package Manager

### Installing

Follow these steps to download and setup the system

* Clone this repo to your preferred environment
```
> git clone git@github.com:wikedzit/StreamStats.git
```

* Use composer and NPM to install all the Laravel dependencies required for this system to run

```
> cd streamstats
> composer install
> npm install
```

Once the installation has finished, you will have all the tools you need to complete the setup.

* First you have to make sure the configurations are set right. All the configuration for this system must be placed
in a __.env__ file. Create this file (if not existing) and copy all the contents of __.env.example__ to this file

* This command should do just fine
```
> cp env.example .env
```
Then replace all the configuration keys with the right values as per your environment

* DB keys
```
DB_CONNECTION=mysql //If you have used a different RDBM e.g postgres or any, replace this with the right value
DB_HOST=127.0.0.1 // Host server
DB_PORT=3306 // DB port
DB_DATABASE=streamstats //Datavase name for this system (YOu can name it anything you want on your local machine)
DB_USERNAME=root
DB_PASSWORD=
```

* API Client Keys. In a development environment these can be left with their default value, but in a production environment the right set of keys should be used. 
```
JWT_KEY=
JWT_TOKEN_TTL=3600
```

* Once all the keys have been set, install all the tables and default data by using Laravel commands
* This command will install all the tables and default API client required to make API calls
* Check _app/database/migrations_ to see the list of tables that the system have

```
> php artisan migrate --seed
```

At this point, you have everything set for the system to run locally on your machine,

### Running the system on your local environment
* An easy way to run the system is by using Laravel built in command.
* By default this command will launch the app at http://127.0.0.1:800X 
```
> php artisan serve
> npm run dev
```

### TODO ITEMS
* Add test cases for the app
* Review and add appropriate error checks
* Update this README for more detailed instructions about the application
* Improve on State management on the front app. Use of proper states manager is a great idea
