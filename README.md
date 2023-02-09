# shortener

## setup

1. Any program for building a web server will do, I personally used xamp.
2. DBMS Mysql, the necessary tables do not need to be pre-created.
3. You will need to register at *localhost/register.php*

## points of interest

1. *shortener.php* - the main page, allows you to generate shortened links, they will be displayed in the list below. There are two buttons below 
```
- the logout: closes the current session, if it exists, and allows you to log in, 
- cleaning links: clears links from the database.
```

2. *personal_account.php* - if there is a user session, displays the statistics of clicks on the links of this particular user, if there is no session, redirects to login.php
