Movies V2
=========

It's an old project of mine which allows sharing your "movie" collection information with everyone oder just some selected friends. Todo that it is possible to:
- organize your movies, series, ... (more types can be added on the fly)
	- besides CRUD operations import/export functions exists to share data between installations of this project and to create a PDF list of the collection
- organize the allowed users and grant them rights to work with the website
- switch the website from public to private
- search "movies" via a simple ajax search field or the advanced search page
- the listings can be switched between two views: title/genre and cover picture
- every "movie" has a detail page with informations about the movie (some can be used to search other movies from this page) and a big version of the cover picture (via overlay)
- the website title can be changed from the admin section (incl. the title inside the header pic)
Everything was implemented with plain PHP (except FPDF) and some JQuery for the JavaScript part.

Since it's one of my first projects which was patched every time i learned something new, the code now looks a little bit patchwork like and a some functions are not realy ideal or missing. So I'm starting to develop a new version ([V3](https://github.com/Spezelechse/movies-v3) ^^).

-----------------------------

###Installation

1. Just create a new database and import the "create_database.sql" File
2. Edit the Config.php (found inside the config folder) and enter your database connection data
3. Now you should be able to login with password and username = admin
