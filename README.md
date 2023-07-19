
# How to run this code

1. Install [XAMPP](https://www.apachefriends.org/).
2. Clone the repository into the filepath below.

 - Windows:

        C:\xampp\htdocs
 - Linux:
        
        /opt/lampp/htdocs


3. Create a constans.php file with the following code in it.
        
        <?php
                define("CONST_DB_HOST","localhost");
                define("CONST_DB_USERNAME","yourUsername");
                define("CONST_DB_PASSWORD","yourPassword");
                define("CONST_DB_NAME","yourDatabaseName");
        ?>
        

4. Use the GUI to start Apache and MySQL or use the command below.
 
        sudo /opt/lampp/lampp start 
        



