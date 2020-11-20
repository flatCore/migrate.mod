## migrate.mod - flatCore CMS Module

This addon will help you if ...

* you want switch from SQLite to MySQL
* you want to import data (entries and categories) from publisher.mod

### How to use this addon

1. First, make sure you have the latest Versions installed (flatCore AND migrate.mod). Make a backup of your website and the database!
2. If you want to switch to MySQL, add your Database Configuration and click the "save data" button
3. If your MySQL Database is connected, you will see the "Add Basic MySQL Tables ..." Button. In Case you have custom columns, there will appear a Button for each Column.
4. Now import the User Data and all Columns from content.sqlite
5. In Case you had installed publisher.mod, you can now import this data (start with the categories). Here you can choose the database (in case you want continue with SQLite).

__Attention__ if you hit the "Generate config file" Button, you can not switch back to the SQLite Database. At least not via the ACP. You can of course delete the file config_database.php from the server. flatCore will try to use the SQLite database.